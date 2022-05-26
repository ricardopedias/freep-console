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
        $this->setName("example2");
        $this->setDescription("Run the 'example2' command");
    }

    protected function handle(Arguments $arguments): void
    {
        // dispara saída padrão para o teste capturar
        $this->line("Command 'example2' executed");
    }
}
