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
        $terminal->setHowToUse("./example command [options] [arguments]");
        $terminal->loadCommandsFrom(__DIR__ . "/FakeApp/ContextOne/src/Commands");
        $terminal->loadCommandsFrom(__DIR__ . "/FakeApp/ContextTwo");
        return $terminal;
    }

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
            "Display command help"
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
        $terminal->run([ "example1", "--help" ]);
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
        $terminal->run([ "example1", "-h" ]);
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
