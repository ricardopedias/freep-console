<?php

declare(strict_types=1);

namespace Tests;

use Freep\Console\Arguments;
use Freep\Console\Command;
use Freep\Console\Option;
use Freep\Console\Terminal;
use PHPUnit\Framework\TestCase;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class CommandExecutionTest extends TestCase
{
    private function terminalFactory(): Terminal
    {
        return new Terminal(__DIR__ . "/FakeApp");
    }

    /** @test */
    public function execution(): void
    {
        $objeto = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
                $this->setName("teste");
                $this->setDescription("Descrição do comando");
                $this->addOption(new Option("-a", "--aaa", 'Descricao opcao 1', Option::OPTIONAL));
            }

            protected function handle(Arguments $arguments): void
            {
                $this->line($this->getName());
                $this->line($this->getDescription());
                $this->line("Total de " . count($this->getOptions()) . " opção");
                $this->line('executado');
            }
        };

        ob_start();
        $objeto->run([]);
        $result = (string)ob_get_clean();

        $this->assertStringContainsString("teste", $result);
        $this->assertStringContainsString("Total de 2 opção", $result);
        $this->assertStringContainsString("Descrição do comando", $result);
        $this->assertStringContainsString("executado", $result);
    }

    /** @test */
    public function defaultDescriptionWithName(): void
    {
        $objeto = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
                $this->setName("teste");
            }

            protected function handle(Arguments $arguments): void
            {
                $this->line($this->getDescription());
            }
        };

        ob_start();
        $objeto->run([]);
        $result = (string)ob_get_clean();

        $this->assertStringContainsString("Executar o comando 'teste'", $result);
    }

    /** @test */
    public function defaultDescriptionWithoutName(): void
    {
        $objeto = new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
            }

            protected function handle(Arguments $arguments): void
            {
                $this->line($this->getDescription());
            }
        };

        ob_start();
        $objeto->run([]);
        $result = (string)ob_get_clean();

        $this->assertStringContainsString("Executar o comando 'sem-nome'", $result);
    }
}
