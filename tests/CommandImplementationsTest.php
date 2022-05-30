<?php

declare(strict_types=1);

namespace Tests;

use Freep\Console\Arguments;
use Freep\Console\Command;
use Freep\Console\Option;
use InvalidArgumentException;
use RuntimeException;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class CommandImplementationsTest extends TestCase
{
    /** @test */
    public function implementationWithInvalidName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The name of a command must be in kebab-case format. Example: command-name");

        new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
                $this->setName('teste com espaço');
            }

            protected function handle(Arguments $arguments): void
            {
            }
        };
    }

    /** @test */
    public function implementationWithOption(): void
    {
        $object = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
                $this->setName('teste');
                $this->addOption(new Option("-p", "--port", 'Descricao opcao 1', Option::REQUIRED));
            }

            protected function handle(Arguments $arguments): void
            {
                $this->line("teste");
            }
        };

        $result = $this->gotcha($object, fn($terminal) => $terminal->run([ "-p", '8080' ]));

        $this->assertStringContainsString("teste", $result);
    }

    /** @test */
    public function implementationWithRequiredOption(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Required options: -p|--port');

        $object = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
                $this->addOption(new Option("-p", "--port", 'Descricao opcao 1', Option::REQUIRED));
            }

            protected function handle(Arguments $arguments): void
            {
            }
        };

        $object->run([]);
    }
}
