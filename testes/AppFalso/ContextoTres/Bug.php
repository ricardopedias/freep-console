<?php
// phpcs:ignoreFile

declare(strict_types=1);

// Sem namespace é impossível instanciar o script

use Freep\Console\Argumentos;
use Freep\Console\Comando;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class Bug extends Comando
{
    protected function inicializar(): void
    {
        $this->setarNome("bug");
        $this->setarDescricao("Executa o comando bugado");
    }

    protected function manipular(Argumentos $argumentos): void
    {
        // ...
    }
}
