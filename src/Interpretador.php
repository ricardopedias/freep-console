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
    private array $mapaNotacoes = [];

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

    /** @param array<int,Option> $configuracao */
    public function __construct(array $configuracao)
    {
        /** @var Opcao $opcao */
        foreach ($configuracao as $opcao) {
            $notacaoPrincipal = $opcao->notacaoPrincipal();
            $notacaoCurta = $opcao->notacaoCurta();
            $notacaoLonga = $opcao->notacaoLonga();

            $this->opcoesConfiguradas[$notacaoPrincipal] = $opcao;
            $this->mapaNotacoes[$notacaoCurta] = $notacaoPrincipal;
            $this->mapaNotacoes[$notacaoLonga] = $notacaoPrincipal;
        }
    }

    private function comporValoresEntreAspas(): void
    {
        $this->opcoesSemChave = (new Composicao($this->opcoesSemChave))->valores();
    }

    /** @param int $indice O indice da opção inválida */
    private function descartarOpcaoInvalida(array & $listaArgumentos, int $indice): void
    {
        unset($listaArgumentos[$indice]);
    }

    /** @param int $indice O indice da opção avulsa */
    private function extrairOpcaoAvulsa(array & $listaArgumentos, int $indice): void
    {
        $this->opcoesSemChave[] = $listaArgumentos[$indice];

        unset($listaArgumentos[$indice]);
    }

    /** @param int $indice O indice da opção */
    private function extrairOpcao(array & $listaArgumentos, int $indice): void
    {
        $notacao = array_shift($listaArgumentos);
        $opcao = $this->obterOpcao($notacao);
        $notacaoPrincipal = $opcao->notacaoPrincipal();

        if ($opcao->booleana() === true || $opcao->valorada() === false) {
            $this->opcoesComChave[$notacaoPrincipal] = '1';
            return;
        }

        $valorComposto = [];
        foreach ($listaArgumentos as $indice => $argumento) {
            // apenas valores são agrupados aqui
            if ($this->formatoNotacao($argumento) === true) {
                break;
            }

            $ultimoNohDoValor = $this->argumentoDeFechamento($argumento);

            $valorComposto[] = $this->removerAspas($argumento);

            // remove o argumento da lista
            unset($listaArgumentos[$indice]);

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

    private function formatoNotacao(string $argumento): bool
    {
        return str_starts_with($argumento, "-");
    }

    private function notacaoValida(string $argumento): bool
    {
        if ($this->formatoNotacao($argumento) === false) {
            return false;
        }

        return isset($this->mapaNotacoes[$argumento]);
    }

    private function obterIndiceAtual(array $listaArgumentos): int
    {
        $chaves = array_keys($listaArgumentos);
        return $chaves[0] ?? -1;
    }

    private function obterOpcao(string $notacao): Opcao
    {
        $notacao = $this->mapaNotacoes[$notacao];
        return $this->opcoesConfiguradas[$notacao];
    }

    public function interpretarArgumentos(string $argumentosDoTerminal): Argumentos
    {
        return $this->interpretarListaArgumentos(explode(' ', $argumentosDoTerminal));
    }

    /** @param array<int,string> $listaArgumentos */
    public function interpretarListaArgumentos(array $listaArgumentos): Argumentos
    {
        while(($indice = $this->obterIndiceAtual($listaArgumentos)) !== -1) {
            $noh = $listaArgumentos[$indice];

            // texto avulso, sem chave especificada
            if ($this->formatoNotacao($noh) === false) {
                $this->extrairOpcaoAvulsa($listaArgumentos, $indice);
                continue;
            }

            // chave inválida de opção
            if ($this->notacaoValida($listaArgumentos[$indice]) === false) {
                $this->descartarOpcaoInvalida($listaArgumentos, $indice);
                continue;
            }

            $this->extrairOpcao($listaArgumentos, $indice);
        }

        $this->popularValoresPadroes();
        $this->verificarObrigatorias();
        $this->popularValoresBooleanos();
        $this->comporValoresEntreAspas();

        return new Argumentos($this->mapaNotacoes, $this->opcoesComChave, $this->opcoesSemChave);
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

    private function verificarObrigatorias(): void
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
