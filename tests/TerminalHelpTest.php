<?php

declare(strict_types=1);

namespace Tests;

use Freep\Console\Commands\Help;
use Freep\Console\Terminal;

class TerminalHelpTest extends TestCase
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

    /** @return array<int,string> */
    private function commandHelpMessageLines(): array
    {
        return [
            "Command: example1",
            "Run the 'example1' command",
            "How to use:",
            "./example example1 [options]",
            "Options:",
            "-h, --help",
            "Display command help",
            "-v, --very-very-very-more-very-long-option",
            "Descricao opcao 1"
        ];
    }

    /** @test */
    public function longDefaultHelpOption(): void
    {
        $terminal = $this->terminalFactory();

        $result = $this->gotcha(
            $terminal,
            fn(Terminal $terminal) => $terminal->run([ "--help" ])
        );

        foreach ($this->helpMessageLines() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }

    /** @test */
    public function shortDefaultHelpOption(): void
    {
        $terminal = $this->terminalFactory();

        $result = $this->gotcha(
            $terminal,
            fn(Terminal $terminal) => $terminal->run([ "-h" ])
        );

        $this->assertEquals(Help::class, $terminal->executedCommand());

        foreach ($this->helpMessageLines() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }

    /** @test */
    public function longHelpOptionFromCommand(): void
    {
        $terminal = $this->terminalFactory();

        $result = $this->gotcha(
            $terminal,
            fn(Terminal $terminal) => $terminal->run([ "example1", "--help" ])
        );

        foreach ($this->commandHelpMessageLines() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }

        // com espacos de alinhamento
        $this->assertStringContainsString("  Descricao opcao 1\n", $result);

        // sem espacos de alinhamento
        $this->assertStringNotContainsString("          Descricao opcao 1\n", $result);
    }

    /** @test */
    public function shortHelpOptionFromCommand(): void
    {
        $terminal = $this->terminalFactory();

        $result = $this->gotcha(
            $terminal,
            fn(Terminal $terminal) => $terminal->run([ "example1", "-h" ])
        );

        foreach ($this->commandHelpMessageLines() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    } 

    /** @test */
    public function helpCommand(): void
    {
        $terminal = $this->terminalFactory();

        $result = $this->gotcha(
            $terminal,
            fn(Terminal $terminal) => $terminal->run([ "help" ])
        );

        $this->assertEquals(Help::class, $terminal->executedCommand());

        foreach ($this->helpMessageLines() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }
}
