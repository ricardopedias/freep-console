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
use Tests\FakeApp\StaticInfo;

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
        $objeto = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
                $this->setName('teste');
                $this->addOption(new Option("-p", "--port", 'Descricao opcao 1', Option::REQUIRED));
            }

            protected function handle(Arguments $arguments): void
            {
                StaticInfo::instance()->addData('result', "teste");
            }
        };

        $objeto->run([ "-p", '8080' ]);
        $result = StaticInfo::instance()->getData('result');
        $this->assertStringContainsString("teste", $result);
    }

    /** @test */
    public function implementationWithRequiredOption(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Required options: -p|--port');

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
