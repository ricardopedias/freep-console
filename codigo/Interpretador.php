<?php

declare(strict_types=1);

namespace Freep\Console;

use RuntimeException;

class Interpretador
{
    /**
     * Mapeamento de notações curtas/longas para notação principal
     * @var array<string,string>
     */
    private array $mapaDeNotacoes = [];

    /**
     * Lista de opções identificáveis pela notação principal
     * @var array<string,Opcao> */
    private array $opcoesConfiguradas = [];

    /**
     * Lista de valores identificáveis pela notação principal
     * @var array<string,mixed> */
    private array $opcoesComChave = [];

    /**
     * Lista de textos especificados que não pertencem a nenhuma opção
     * @var array<int,string> */
    private array $opcoesSemChave = [];

    /** @param array<int,Opcao> $configuracao */
    public function __construct(array $configuracao)
    {
        /** @var Opcao $opcao */
        foreach ($configuracao as $opcao) {
            $notacaoPrincipal = $opcao->notacaoPrincipal();
            $notacaoCurta = $opcao->notacaoCurta();
            $notacaoLonga = $opcao->notacaoLonga();

            $this->opcoesConfiguradas[$notacaoPrincipal] = $opcao;
            $this->mapaDeNotacoes[$notacaoCurta] = $notacaoPrincipal;
            $this->mapaDeNotacoes[$notacaoLonga] = $notacaoPrincipal;
        }
    }

    private function comporValoresEntreAspas(): void
    {
        $this->opcoesSemChave = (new Composicao($this->opcoesSemChave))->valores();
    }

    /**
     * @param array<int,string> $listaDeArgumentos
     * @param int $indice O indice da opção inválida
     */
    private function descartarOpcaoInvalida(array &$listaDeArgumentos, int $indice): void
    {
        unset($listaDeArgumentos[$indice]);
    }

    /**
     * @param array<int,string> $listaDeArgumentos
     * @param int $indice O indice da opção avulsa
     */
    private function extrairOpcaoAvulsa(array &$listaDeArgumentos, int $indice): void
    {
        $this->opcoesSemChave[] = $listaDeArgumentos[$indice];

        unset($listaDeArgumentos[$indice]);
    }

    /**
     * @param array<int,string> $listaDeArgumentos
     * @param int $indice O indice da opção
     */
    private function extrairOpcao(array &$listaDeArgumentos, int $indice): void
    {
        $notacao = array_shift($listaDeArgumentos);
        $opcao = $this->opcao((string)$notacao);
        $notacaoPrincipal = $opcao->notacaoPrincipal();

        if ($opcao->booleana() === true || $opcao->valorada() === false) {
            $this->opcoesComChave[$notacaoPrincipal] = '1';
            return;
        }

        $valorComposto = [];
        foreach ($listaDeArgumentos as $indice => $argumento) {
            // apenas valores são agrupados aqui
            if ($this->formatoDeNotacao($argumento) === true) {
                break;
            }

            $ultimoNohDoValor = $this->argumentoDeFechamento($argumento);

            $valorComposto[] = $this->removerAspas($argumento);

            // remove o argumento da lista
            unset($listaDeArgumentos[$indice]);

            if ($ultimoNohDoValor === true) {
                break;
            }
        }

        if ($valorComposto === [] && $opcao->valorPadrao() !== "") {
            $valorComposto[] = $opcao->valorPadrao();
        }

        if ($valorComposto === []) {
            throw new RuntimeException("A opção '{$notacao}' requer um valor");
        }

        $this->opcoesComChave[$notacaoPrincipal] = implode(" ", $valorComposto);
    }

    private function removerAspas(string $argumento): string
    {
        return trim(trim($argumento, "'"), '"');
    }

    private function argumentoDeFechamento(string $argumento): bool
    {
        return str_ends_with($argumento, "'") || str_ends_with($argumento, '"');
    }

    private function formatoDeNotacao(string $argumento): bool
    {
        return str_starts_with($argumento, "-");
    }

    private function notacaoValida(string $argumento): bool
    {
        if ($this->formatoDeNotacao($argumento) === false) {
            return false;
        }

        return isset($this->mapaDeNotacoes[$argumento]);
    }

    /** @param array<int,string> $listaDeArgumentos */
    private function indiceAtual(array $listaDeArgumentos): int
    {
        $chaves = array_keys($listaDeArgumentos);
        return $chaves[0] ?? -1;
    }

    private function opcao(string $notacao): Opcao
    {
        $notacao = $this->mapaDeNotacoes[$notacao];
        return $this->opcoesConfiguradas[$notacao];
    }

    public function interpretarArgumentos(string $argumentosDoTerminal): Argumentos
    {
        return $this->interpretarListaDeArgumentos(explode(' ', $argumentosDoTerminal));
    }

    /** @param array<int,string> $listaDeArgumentos */
    public function interpretarListaDeArgumentos(array $listaDeArgumentos): Argumentos
    {
        while (($indice = $this->indiceAtual($listaDeArgumentos)) !== -1) {
            $noh = $listaDeArgumentos[$indice];

            // texto avulso, sem chave especificada
            if ($this->formatoDeNotacao($noh) === false) {
                $this->extrairOpcaoAvulsa($listaDeArgumentos, $indice);
                continue;
            }

            // chave inválida de opção
            if ($this->notacaoValida($listaDeArgumentos[$indice]) === false) {
                $this->descartarOpcaoInvalida($listaDeArgumentos, $indice);
                continue;
            }

            $this->extrairOpcao($listaDeArgumentos, $indice);
        }

        $this->popularValoresPadroes();
        $this->verificarObrigatoriedade();
        $this->popularValoresBooleanos();
        $this->comporValoresEntreAspas();

        return new Argumentos($this->mapaDeNotacoes, $this->opcoesComChave, $this->opcoesSemChave);
    }

    private function popularValoresPadroes(): void
    {
        /** @var Opcao $opcao */
        foreach ($this->opcoesConfiguradas as $opcao) {
            if ($opcao->valorPadrao() === "") {
                continue;
            }

            if (isset($this->opcoesComChave[$opcao->notacaoPrincipal()]) === true) {
                continue;
            }

            $this->opcoesComChave[$opcao->notacaoPrincipal()] = $opcao->valorPadrao();
        }
    }

    private function popularValoresBooleanos(): void
    {
        /** @var Opcao $opcao */
        foreach ($this->opcoesConfiguradas as $opcao) {
            if ($opcao->booleana() === false) {
                continue;
            }

            if (isset($this->opcoesComChave[$opcao->notacaoPrincipal()]) === true) {
                continue;
            }

            $this->opcoesComChave[$opcao->notacaoPrincipal()] = '0';
        }
    }

    private function verificarObrigatoriedade(): void
    {
        $obrigatorias = [];

        /** @var Opcao $opcao */
        foreach ($this->opcoesConfiguradas as $opcao) {
            if ($opcao->obrigatoria() === false) {
                continue;
            }

            if (isset($this->opcoesComChave[$opcao->notacaoPrincipal()]) === true) {
                continue;
            }

            $obrigatorias[] = $opcao->notacaoCurta() . "|" . $opcao->notacaoLonga();
        }

        if ($obrigatorias === []) {
            return;
        }

        $dica = implode(", ", $obrigatorias);
        throw new RuntimeException(sprintf("Opções obrigatórias: %s", $dica));
    }
}
