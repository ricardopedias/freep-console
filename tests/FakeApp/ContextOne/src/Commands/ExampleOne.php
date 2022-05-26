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
        $this->setName("example1");
        $this->setDescription("Run the 'example1' command");
        $this->setHowToUse("./example example1 [options]");
    }

    protected function handle(Arguments $arguments): void
    {
        // dispara saída padrão para o teste capturar
        $this->line("Command 'example1' executed");
    }
}
