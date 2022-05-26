<?php

declare(strict_types=1);

namespace Tests\FakeApp\ContextTwo;

use Freep\Console\Arguments;
use Freep\Console\Command;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class ExampleTwo extends Command
{
    protected function initialize(): void
    {
        $this->setName("exemplo2");
        $this->setDescription("Executa o comando exemplo2");
    }

    protected function handle(Arguments $arguments): void
    {
        // dispara saída padrão para o teste capturar
        $this->line("exemplo2 executado");
    }
}
