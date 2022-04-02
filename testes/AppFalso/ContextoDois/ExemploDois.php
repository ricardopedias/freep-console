<?php

declare(strict_types=1);

namespace Testes\AppFalso\ContextoDois;

use Freep\Console\Argumentos;
use Freep\Console\Comando;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class ExemploDois extends Comando
{
    protected function inicializar(): void
    {
        $this->setarNome("exemplo2");
        $this->setarDescricao("Executa o comando exemplo2");
    }

    protected function manipular(Argumentos $argumentos): void
    {
        // dispara saída padrão para o teste capturar
        $this->linha("exemplo2 executado");
    }
}
