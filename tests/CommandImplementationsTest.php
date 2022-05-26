<?php

declare(strict_types=1);

namespace Tests;

use Freep\Console\Arguments;
use Freep\Console\Command;
use Freep\Console\Option;
use Freep\Console\Terminal;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class CommandImplementationsTest extends TestCase
{
    private function terminalFactory(): Terminal
    {
        return new Terminal(__DIR__ . "/FakeApp");
    }

    /** @test */
    public function implementationWithInvalidName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("O nome de um comando deve ser no formato kebab-case. Ex: nome-do-comando");

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
        $objeto = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
                $this->setName('teste');
                $this->addOption(new Option("-p", "--port", 'Descricao opcao 1', Option::REQUIRED));
            }

            protected function handle(Arguments $arguments): void
            {
                echo "teste";
            }
        };

        ob_start();
        $objeto->run([ "-p", '8080' ]);
        $result = (string)ob_get_clean();
        $this->assertStringContainsString("teste", $result);
    }

    /** @test */
    public function implementationWithRequiredOption(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Opções obrigatórias: -p|--port');

        $objeto = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
                $this->addOption(new Option("-p", "--port", 'Descricao opcao 1', Option::REQUIRED));
            }

            protected function handle(Arguments $arguments): void
            {
            }
        };

        $objeto->run([]);
    }
}
