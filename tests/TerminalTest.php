<?php

declare(strict_types=1);

namespace Tests;

use Freep\Console\Terminal;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\FakeApp\ContextOne\src\Commands\ExampleOne;
use Tests\FakeApp\ContextTwo\ExampleTwo;

class TerminalTest extends TestCase
{
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

    private function terminalFactory(): Terminal
    {
        $terminal = new Terminal(__DIR__ . "/FakeApp");
        $terminal->setHowToUse("./superapp comando [opcoes] [argumentos]");
        $terminal->loadCommandsFrom(__DIR__ . "/FakeApp/ContextOne/src/Commands");
        $terminal->loadCommandsFrom(__DIR__ . "/FakeApp/ContextTwo");
        return $terminal;
    }

    /** @test */
    public function loadCommandsFromException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $terminal = new Terminal(__DIR__ . "/FakeApp");
        $terminal->loadCommandsFrom("/caminho-inexistente");
    }

    /** @test */
    public function withoutArguments(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->run([]);
        $result = (string)ob_get_clean();

        $this->assertEquals("nao", $terminal->executedCommand());
        $this->assertEmpty($result);
    }

    /** @test */
    public function nonExistentCommand(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->run([ "blabla" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals("nao", $terminal->executedCommand());

        foreach ($this->helpMessageLines() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }

    /** @test */
    public function exampleCommandOne(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->run([ "exemplo1" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals(ExampleOne::class, $terminal->executedCommand());
        $this->assertStringContainsString("exemplo1 executado", $result);
    }

    /** @test */
    public function exampleCommandTwo(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->run([ "exemplo2" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals(ExampleTwo::class, $terminal->executedCommand());
        $this->assertStringContainsString("exemplo2 executado", $result);
    }

    /** @test */
    public function exampleCommandException(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->run([ "exemplo-excecao" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals("nao", $terminal->executedCommand());
        $this->assertStringContainsString("exemplo-excecao lançou exceção", $result);
    }

    /** @test */
    public function exampleCommandBadImplementation(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->loadCommandsFrom(__DIR__ . "/FakeApp/ContextThree");
        $terminal->run([ "bug" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals("nao", $terminal->executedCommand());
        $this->assertStringContainsString("Não é possível extrair o namespace do arquivo", $result);
    }
}
