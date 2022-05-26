<?php

declare(strict_types=1);

namespace Tests\FakeApp\ContextOne\src\Commands;

use Freep\Console\Arguments;
use Freep\Console\Command;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class ExampleOne extends Command
{
    protected function initialize(): void
    {
        $this->setName("exemplo1");
        $this->setDescription("Executa o comando exemplo1");
        $this->setHowToUse("./superapp exemplo1 [opcoes]");
    }

    protected function handle(Arguments $arguments): void
    {
        // dispara saída padrão para o teste capturar
        $this->line("exemplo1 executado");
    }
}
