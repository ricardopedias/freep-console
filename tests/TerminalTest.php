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
            "How to use:",
            "./example command [options] [arguments]",

            "Options:",
            "-h, --help",
            "Display help information",

            "Available commands:",
            "help",
            "Display help information",
            "example-exception",
            "Run the 'example-exception' command",
            "example1",
            "Run the 'example1' command",
            "example2",
            "Run the 'example2' command"
        ];
    }

    private function terminalFactory(): Terminal
    {
        $terminal = new Terminal(__DIR__ . "/FakeApp");
        $terminal->setHowToUse("./example command [options] [arguments]");
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

        $this->assertEquals("no", $terminal->executedCommand());
        $this->assertEmpty($result);
    }

    /** @test */
    public function nonExistentCommand(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->run([ "blabla" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals("no", $terminal->executedCommand());

        foreach ($this->helpMessageLines() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }

    /** @test */
    public function exampleCommandOne(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->run([ "example1" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals(ExampleOne::class, $terminal->executedCommand());
        $this->assertStringContainsString("Command 'example1' executed", $result);
    }

    /** @test */
    public function exampleCommandTwo(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->run([ "example2" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals(ExampleTwo::class, $terminal->executedCommand());
        $this->assertStringContainsString("Command 'example2' executed", $result);
    }

    /** @test */
    public function exampleCommandException(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->run([ "example-exception" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals("no", $terminal->executedCommand());
        $this->assertStringContainsString("Command 'example-exception' threw exception", $result);
    }

    /** @test */
    public function exampleCommandBadImplementation(): void
    {
        ob_start();
        $terminal = $this->terminalFactory();
        $terminal->loadCommandsFrom(__DIR__ . "/FakeApp/ContextThree");
        $terminal->run([ "bug" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals("no", $terminal->executedCommand());
        $this->assertStringContainsString("Unable to extract namespace from file", $result);
    }
}
