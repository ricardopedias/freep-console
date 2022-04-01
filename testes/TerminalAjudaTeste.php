<?php

declare(strict_types=1);

namespace Testes;

use Freep\Console\Comandos\Ajuda;
use Freep\Console\Terminal;
use PHPUnit\Framework\TestCase;

class TerminalAjudaTeste extends TestCase
{
    private function fabricarTerminal(): Terminal
    {
        $terminal = new Terminal(__DIR__ . "/AppFalso");
        $terminal->carregarComandosDe(__DIR__ . "/AppFalso/ContextoUm/src/Comandos");
        $terminal->carregarComandosDe(__DIR__ . "/AppFalso/ContextoDois");
        return $terminal;
    }

    private function linhasMensagemAjuda(): array
    {
        return [
            "Modo de usar:",
            "./freep comando [opcoes] [argumentos]",

            "Opções:",
            "-a, --ajuda",
            "Exibe as informações de ajuda",

            "Comandos disponíveis:",
            "ajuda",
            "Exibe as informações de ajuda",
            "exemplo-excecao",
            "Executa o comando exemplo-excecao",
            "exemplo1",
            "Executa o comando exemplo1",
            "exemplo2",
            "Executa o comando exemplo2"
        ];
    }

    private function linhasMensagemAjudaComando(): array
    {
        return [
            "Comando: exemplo1",
            "Executa o comando exemplo1",
            "Modo de usar:",
            "./freep exemplo1 [opcoes]",
            "Opções:",
            "-a, --ajuda",
            "Exibe a ajuda do comando"
        ];
    }

    /** @test */
    public function opcaoAjudaPadraoLonga()
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->executar([ "--ajuda" ]);
        $result = ob_get_clean();

        foreach ($this->linhasMensagemAjuda() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }

    /** @test */
    public function opcaoAjudaPadraoCurta()
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->executar([ "-a" ]);
        $result = ob_get_clean();

        $this->assertEquals(Ajuda::class, $terminal->comandoExecutado());

        foreach ($this->linhasMensagemAjuda() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }

    /** @test */
    public function opcaoAjudaLongaDoComando()
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->executar([ "exemplo1", "--ajuda" ]);
        $result = ob_get_clean();

        foreach ($this->linhasMensagemAjudaComando() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }

    /** @test */
    public function opcaoAjudaCurtaDoComando()
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->executar([ "exemplo1", "-a" ]);
        $result = ob_get_clean();

        foreach ($this->linhasMensagemAjudaComando() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }

    /** @test */
    public function comandoAjuda()
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->executar([ "ajuda" ]);
        $result = ob_get_clean();

        $this->assertEquals(Ajuda::class, $terminal->comandoExecutado());

        foreach ($this->linhasMensagemAjuda() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }
}
