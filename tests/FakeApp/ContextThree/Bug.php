<?php
// phpcs:ignoreFile

declare(strict_types=1);

// Sem namespace é impossível instanciar o script

use Freep\Console\Arguments;
use Freep\Console\Command;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class Bug extends Command
{
    protected function initialize(): void
    {
        $this->setName("bug");
        $this->setDescription("Executa o comando bugado");
    }

    protected function handle(Arguments $arguments): void
    {
        // ...
    }
}
