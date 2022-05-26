<?php

declare(strict_types=1);

namespace Tests;

use Freep\Console\Arguments;
use Freep\Console\Command;
use Freep\Console\Terminal;
use PHPUnit\Framework\TestCase;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class CommandOutputTest extends TestCase
{
    private function terminalFactory(): Terminal
    {
        return new Terminal(__DIR__ . "/FakeApp");
    }

    /** @test */
    public function executionWithTextPrint(): void
    {
        $objeto = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }


            protected function handle(Arguments $arguments): void
            {
                $this->line("exibida mensagem de texto");
            }
        };

        ob_start();
        $objeto->run([]);
        $result = (string)ob_get_clean();

        $this->assertStringContainsString("exibida mensagem de texto", $result);
    }

    /** @test */
    public function executionWithErrorPrint(): void
    {
        $objeto = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                $this->error("exibida mensagem de erro");
            }
        };

        ob_start();
        $objeto->run([]);
        $result = (string)ob_get_clean();

        $this->assertStringContainsString("exibida mensagem de erro", $result);
    }

    /** @test */
    public function executionWithInfoPrint(): void
    {
        $objeto = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                $this->info("exibida mensagem informativa");
            }
        };

        ob_start();
        $objeto->run([]);
        $result = (string)ob_get_clean();

        $this->assertStringContainsString("exibida mensagem informativa", $result);
    }

    /** @test */
    public function executionWithWarningPrint(): void
    {
        $objeto = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                $this->warning("exibida mensagem de alerta");
            }
        };

        ob_start();
        $objeto->run([]);
        $result = (string)ob_get_clean();

        $this->assertStringContainsString("exibida mensagem de alerta", $result);
    }
}
