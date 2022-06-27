<?php

declare(strict_types=1);

namespace Tests\FakeApp\ContextTwo;

// namespace inválido de propósito

use Freep\Console\Arguments;
use Freep\Console\Command;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class ExampleFourInvalid extends Command // nome da classe diferente do arquivo
{
    protected function initialize(): void
    {
        $this->setName("example4");
        $this->setDescription("Run the 'example2' command");
    }

    protected function handle(Arguments $arguments): void
    {
        // dispara saída padrão para o teste capturar
        $this->line("Command 'example2' executed");
    }
}
