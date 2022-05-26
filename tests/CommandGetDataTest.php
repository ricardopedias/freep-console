<?php

declare(strict_types=1);

namespace Tests;

use Freep\Console\Arguments;
use Freep\Console\Command;
use Freep\Console\Terminal;
use PHPUnit\Framework\TestCase;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class CommandGetDataTest extends TestCase
{
    private function terminalFactory(): Terminal
    {
        return new Terminal(__DIR__ . "/FakeApp");
    }

    /** @test */
    public function getAppPath(): void
    {
        $objeto = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                echo $this->getAppPath();
            }
        };

        ob_start();
        $objeto->run([]);
        $result = (string)ob_get_clean();

        $path = (string)realpath(__DIR__ . "/FakeApp");
        $this->assertStringContainsString($path, $result);
    }

    /** @test */
    public function getAppPathWithSufix(): void
    {
        $objeto = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                echo $this->getAppPath("teste/de/sufixo");
            }
        };

        ob_start();
        $objeto->run([]);
        $result = (string)ob_get_clean();

        $path = (string)realpath(__DIR__ . "/FakeApp/teste/de/sufixo");
        $this->assertStringContainsString($path, $result);
    }

    /** @test */
    public function getAppPathWithCleanedBarsSufix(): void
    {
        $objeto = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                // barras são ajustadas automaticamente na montagem do caminho com sufixo
                echo $this->getAppPath("/teste/de/sufixo/");
            }
        };

        ob_start();
        $objeto->run([]);
        $result = (string)ob_get_clean();

        $path = (string)realpath(__DIR__ . "/FakeApp/teste/de/sufixo");
        $this->assertStringContainsString($path, $result);
    }

    /** @test */
    public function getAppPathWithCleanedRightBarsSufix(): void
    {
        $objeto = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                // barras são ajustadas automaticamente na montagem do caminho com sufixo
                echo $this->getAppPath("teste/de/sufixo/");
            }
        };

        ob_start();
        $objeto->run([]);
        $result = (string)ob_get_clean();

        $path = (string)realpath(__DIR__ . "/FakeApp/teste/de/sufixo");
        $this->assertStringContainsString($path, $result);
    }

    /** @test */
    public function getAppPathWithCleanedLeftBarsSufix(): void
    {
        $objeto = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                // barras são ajustadas automaticamente na montagem do caminho com sufixo
                echo $this->getAppPath("/teste/de/sufixo");
            }
        };

        ob_start();
        $objeto->run([]);
        $result = (string)ob_get_clean();

        $path = (string)realpath(__DIR__ . "/FakeApp/teste/de/sufixo");
        $this->assertStringContainsString($path, $result);
    }
}
