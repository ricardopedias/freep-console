<?php

declare(strict_types=1);

namespace Tests;

use Freep\Console\Commands\Help;
use Freep\Console\Terminal;
use PHPUnit\Framework\TestCase;

class TerminalHelpTest extends TestCase
{
    private function terminalFactory(): Terminal
    {
        $terminal = new Terminal(__DIR__ . "/FakeApp");
        $terminal->setHowToUse("./superapp comando [opcoes] [argumentos]");
        $terminal->loadCommandsFrom(__DIR__ . "/FakeApp/ContextOne/src/Commands");
        $terminal->loadCommandsFrom(__DIR__ . "/FakeApp/ContextTwo");
        return $terminal;
    }

    /** @return array<int,string> */
    private function helpMessageLines(): array
    {
        return [
            "Modo de usar:",
            "./superapp comando [opcoes] [argumentos]",

            "Opções:",
            "-h, --help",
            "Exibe as informações de ajuda",

            "Comandos disponíveis:",
            "help",
            "Exibe as informações de ajuda",
            "exemplo-excecao",
            "Executa o comando exemplo-excecao",
            "exemplo1",
            "Executa o comando exemplo1",
            "exemplo2",
            "Executa o comando exemplo2"
        ];
    }

    /** @return array<int,string> */
    private function commandHelpMessageLines(): array
    {
        return [
            "Comando: exemplo1",
            "Executa o comando exemplo1",
            "Modo de usar:",
            "./superapp exemplo1 [opcoes]",
            "Opções:",
            "-h, --help",
            "Exibe a ajuda do comando"
        ];
    }

    /** @test */
    public function longDefaultHelpOption(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->run([ "--help" ]);
        $result = (string)ob_get_clean();

        foreach ($this->helpMessageLines() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }

    /** @test */
    public function shortDefaultHelpOption(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->run([ "-h" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals(Help::class, $terminal->executedCommand());

        foreach ($this->helpMessageLines() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }

    /** @test */
    public function longHelpOptionFromCommand(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->run([ "exemplo1", "--help" ]);
        $result = (string)ob_get_clean();

        foreach ($this->commandHelpMessageLines() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }

    /** @test */
    public function shortHelpOptionFromCommand(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->run([ "exemplo1", "-h" ]);
        $result = (string)ob_get_clean();

        foreach ($this->commandHelpMessageLines() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }

    /** @test */
    public function helpCommand(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->run([ "help" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals(Help::class, $terminal->executedCommand());

        foreach ($this->helpMessageLines() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }
}
