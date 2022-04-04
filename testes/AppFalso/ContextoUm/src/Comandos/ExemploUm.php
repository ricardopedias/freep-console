<?php

declare(strict_types=1);

namespace Testes\AppFalso\ContextoUm\src\Comandos;

use Freep\Console\Argumentos;
use Freep\Console\Comando;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class ExemploUm extends Comando
{
    protected function inicializar(): void
    {
        $this->setarNome("exemplo1");
        $this->setarDescricao("Executa o comando exemplo1");
        $this->setarModoDeUsar("./superapp exemplo1 [opcoes]");
    }

    protected function manipular(Argumentos $argumentos): void
    {
        // dispara saída padrão para o teste capturar
        $this->linha("exemplo1 executado");
    }
}
