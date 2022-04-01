<?php

declare(strict_types=1);

namespace Freep\Console\Comandos;

use Freep\Console\Argumentos;
use Freep\Console\Comando;

class Ajuda extends Comando
{
    protected function inicializar(): void
    {
        $this->setarNome("ajuda");
        $this->setarDescricao("Exibe as informações de ajuda");
    }

    protected function manipular(Argumentos $argumentos): void
    {
        $this->imprimirSecao("Modo de usar:");
        $this->linha("  ./freep comando [opcoes] [argumentos]");

        $this->imprimirSecao("Opções:");

        $this->imprimirOpcao("-a", "--ajuda", $this->descricao());

        $this->imprimirSecao("Comandos disponíveis:");

        $listaComandos = $this->obterComandos();
        foreach ($listaComandos as $comando) {
            $this->imprimirComando($comando->nome(), $comando->descricao());
        }
    }

    private function obterComandos(): array
    {
        $lista = [];
        $listaComandos = $this->obterTerminal()->obterListaComandos();
        foreach ($listaComandos as $arquivoComando) {
            $nomeCompletoClasse = $this->obterTerminal()->interpretarNomeClasse($arquivoComando);

            if (class_exists($nomeCompletoClasse) === false) {
                continue;
            }
    
            $objetoComando = (new $nomeCompletoClasse($this->obterTerminal()));
            $lista[] = $objetoComando;
        }

        return $lista;
    }

    private function imprimirSecao(string $descricao): void
    {
        $this->linha("\n\033[0;33m {$descricao} \033[0m");
    }

    private function imprimirOpcao(string $curta, string $longa, string $descricao): void
    {
        $argumento = "{$curta}, {$longa}";

        $coluna = 20;
        $caracteres = mb_strlen($argumento);
        $espacamento = $caracteres < $coluna
            ? str_repeat(" ", $coluna - $caracteres)
            : " ";

        $involucro = "\033[0;32m%s \033[0m %s";
        $this->linha(sprintf($involucro, $argumento, $espacamento . $descricao));
    }

    private function imprimirComando(string $comando, string $descricao): void
    {
        $coluna = 20;
        $caracteres = mb_strlen($comando);
        $espacamento = $caracteres < $coluna
            ? str_repeat(" ", $coluna - $caracteres)
            : " ";

        $involucro = "\033[0;32m%s \033[0m %s";
        $this->linha(sprintf($involucro, $comando, $espacamento . $descricao));
    }
}
