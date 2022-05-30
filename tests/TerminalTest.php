<?php

declare(strict_types=1);

namespace Tests;

use Freep\Console\Terminal;
use InvalidArgumentException;
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
            "very-very-very-more-very-long-command",
            "Run the 'example2' command"
        ];
    }

    /** @test */
    public function constructorException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectErrorMessage('The specified application directory does not exist');

        new Terminal(__DIR__ . "/NotExists");
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
        $terminal = $this->terminalFactory();

        $result = $this->gotcha($terminal, fn($terminal) => $terminal->run([]));

        $this->assertEquals("no", $terminal->executedCommand());
        $this->assertEmpty($result);
    }

    /** @test */
    public function nonExistentCommand(): void
    {
        $terminal = $this->terminalFactory();

        $result = $this->gotcha($terminal, fn($terminal) => $terminal->run([ "blabla" ]));

        $this->assertEquals("no", $terminal->executedCommand());

        foreach ($this->helpMessageLines() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }

    /** @test */
    public function exampleCommandOne(): void
    {
        $terminal = $this->terminalFactory();

        $result = $this->gotcha($terminal, fn($terminal) => $terminal->run([ "example1" ]));

        $this->assertEquals(ExampleOne::class, $terminal->executedCommand());
        $this->assertStringContainsString("Command 'example1' executed", $result);
    }

    /** @test */
    public function exampleCommandTwo(): void
    {
        $terminal = $this->terminalFactory();

        $result = $this->gotcha(
            $terminal,
            fn($terminal) => $terminal->run([ "very-very-very-more-very-long-command" ])
        );

        $this->assertEquals(ExampleTwo::class, $terminal->executedCommand());
        $this->assertStringContainsString("Command 'example2' executed", $result);
    }

    /** @test */
    public function exampleCommandException(): void
    {
        $terminal = $this->terminalFactory();

        $result = $this->gotcha($terminal, fn($terminal) => $terminal->run([ "example-exception" ]));

        $this->assertEquals("no", $terminal->executedCommand());
        $this->assertStringContainsString("Command 'example-exception' threw exception", $result);
    }

    /** @test */
    public function exampleCommandBadImplementation(): void
    {
        $terminal = $this->terminalFactory();
        $terminal->loadCommandsFrom(__DIR__ . "/FakeApp/ContextThree");

        $result = $this->gotcha($terminal, fn($terminal) => $terminal->run([ "bug" ]));

        $this->assertEquals("no", $terminal->executedCommand());
        $this->assertStringContainsString("Unable to extract namespace from file", $result);
    }

    /** @test */
    public function exampleCommandClassWithInvalidName(): void
    {
        $terminal = $this->terminalFactory();
        $terminal->loadCommandsFrom(__DIR__ . "/FakeApp/ContextFour");

        $result = $this->gotcha($terminal, fn($terminal) => $terminal->run([ "example4" ]));

        $this->assertEquals("no", $terminal->executedCommand());
        $this->assertStringContainsString(
            "The file '/application/tests/FakeApp/ContextFour/ExampleFour.php' " .
            "not contains a 'Tests\FakeApp\ContextTwo\ExampleFour' class",
            $result
        );
    }
}
