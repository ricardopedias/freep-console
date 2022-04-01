<?php

declare(strict_types=1);

namespace Testes\AppFalso\ContextoUm\src\Comandos;

use Exception;
use Freep\Console\Argumentos;
use Freep\Console\Comando;

class ExemploExcecao extends Comando
{
    public function inicializar(): void
    {
        $this->setarNome("exemplo-excecao");
        $this->setarDescricao("Executa o comando exemplo-excecao");
    }

    protected function manipular(Argumentos $argumentos): void
    {
        throw new Exception("exemplo-excecao lançou exceção");
    }
}
