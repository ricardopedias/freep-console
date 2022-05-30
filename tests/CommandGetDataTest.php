<?php

declare(strict_types=1);

namespace Tests;

use Freep\Console\Arguments;
use Freep\Console\Command;
use OutOfRangeException;
use Tests\FakeApp\StaticInfo;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class CommandGetDataTest extends TestCase
{
    /** @test */
    public function getAppPath(): void
    {
        $object = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                $this->line($this->getAppPath());
            }
        };

        $result = $this->gotcha($object, fn($terminal) => $terminal->run([]));

        $this->assertStringContainsString(__DIR__ . "/FakeApp", $result);
    }

    /** @test */
    public function getAppPathWithSufix(): void
    {
        $object = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                $this->line($this->getAppPath("teste/de/sufixo"));
            }
        };

        $result = $this->gotcha($object, fn($terminal) => $terminal->run([]));

        $this->assertStringContainsString(__DIR__ . "/FakeApp/teste/de/sufixo", $result);
    }

    /** @test */
    public function getAppPathWithCleanedBarsSufix(): void
    {
        $object = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                // barras são ajustadas automaticamente na montagem do caminho com sufixo
                $this->line($this->getAppPath("/teste/de/sufixo"));
            }
        };

        $result = $this->gotcha($object, fn($terminal) => $terminal->run([]));

        $this->assertStringContainsString(__DIR__ . "/FakeApp/teste/de/sufixo", $result);
    }

    /** @test */
    public function getAppPathWithCleanedRightBarsSufix(): void
    {
        $object = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                // barras são ajustadas automaticamente na montagem do caminho com sufixo
                $this->line($this->getAppPath("teste/de/sufixo"));
            }
        };

        $result = $this->gotcha($object, fn($terminal) => $terminal->run([]));
        
        $this->assertStringContainsString(__DIR__ . "/FakeApp/teste/de/sufixo", $result);
    }

    /** @test */
    public function getAppPathWithCleanedLeftBarsSufix(): void
    {
        $object = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                // barras são ajustadas automaticamente na montagem do caminho com sufixo
                $this->line($this->getAppPath("/teste/de/sufixo"));
            }
        };

        $result = $this->gotcha($object, fn($terminal) => $terminal->run([]));

        $this->assertStringContainsString(__DIR__ . "/FakeApp/teste/de/sufixo", $result);
    }

    /** @test */
    public function getArgumentList(): void
    {
        $object = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                // barras são ajustadas automaticamente na montagem do caminho com sufixo
                $this->line(json_encode($arguments->getArgumentList()));
            }
        };

        $result = $this->gotcha($object, fn($terminal) => $terminal->run([ "Ricardo Pereira" ]));

        $this->assertEquals(json_encode([ "Ricardo Pereira" ]) . "\n", $result);
    }

    /** @test */
    public function invalidOptionException(): void
    {
        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage("Option '-x' is invalid");

        $object = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                // barras são ajustadas automaticamente na montagem do caminho com sufixo
                $arguments->getOption('-x');
            }
        };

        $object->run([]);
    }
}
