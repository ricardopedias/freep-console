<?php

declare(strict_types=1);

namespace Tests\FakeApp\ContextOne\src\Commands;

use Exception;
use Freep\Console\Arguments;
use Freep\Console\Command;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class ExampleException extends Command
{
    public function initialize(): void
    {
        $this->setName("exemplo-excecao");
        $this->setDescription("Executa o comando exemplo-excecao");
    }

    protected function handle(Arguments $arguments): void
    {
        throw new Exception("exemplo-excecao lançou exceção");
    }
}
