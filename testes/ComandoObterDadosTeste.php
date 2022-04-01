<?php

declare(strict_types=1);

namespace Test\Console;

use Freep\Console\Argumentos;
use Freep\Console\Comando;
use Freep\Console\Terminal;
use PHPUnit\Framework\TestCase;

class ComandoObterDadosTeste extends TestCase
{
    private function fabricarTerminal(): Terminal
    {
        return new Terminal(__DIR__ . "/AppFalso");
    }

    /** @test */
    public function obterCaminhoRaiz(): void
    {
        $objeto = new class($this->fabricarTerminal()) extends Comando {
            protected function inicializar(): void
            {}

            protected function manipular(Argumentos $argumentos): void
            {
                echo $this->caminhoAplicacao();
            }
        };

        ob_start();
        $objeto->executar([]);
        $result = (string)ob_get_clean();

        $path = (string)realpath(__DIR__ . "/AppFalso");
        $this->assertStringContainsString($path, $result);
    }

    /** @test */
    public function obterCaminhoRaizComSufixo(): void
    {
        $objeto = new class($this->fabricarTerminal()) extends Comando {
            protected function inicializar(): void
            {}

            protected function manipular(Argumentos $argumentos): void
            {
                echo $this->caminhoAplicacao("teste/de/sufixo");
            }
        };

        ob_start();
        $objeto->executar([]);
        $result = (string)ob_get_clean();

        $path = (string)realpath(__DIR__ . "/AppFalso/teste/de/sufixo");
        $this->assertStringContainsString($path, $result);
    }

    /** @test */
    public function obterCaminhoRaizComSufixoLimpandoBarras(): void
    {
        $objeto = new class($this->fabricarTerminal()) extends Comando {
            protected function inicializar(): void
            {}

            protected function manipular(Argumentos $argumentos): void
            {
                // barras são ajustadas automaticamente na montagem do caminho com sufixo
                echo $this->caminhoAplicacao("/teste/de/sufixo/");
            }
        };

        ob_start();
        $objeto->executar([]);
        $result = (string)ob_get_clean();

        $path = (string)realpath(__DIR__ . "/AppFalso/teste/de/sufixo");
        $this->assertStringContainsString($path, $result);
    }

    /** @test */
    public function obterCaminhoRaizComSufixoLimpandoBarraDireita(): void
    {
        $objeto = new class($this->fabricarTerminal()) extends Comando {
            protected function inicializar(): void
            {}

            protected function manipular(Argumentos $argumentos): void
            {
                // barras são ajustadas automaticamente na montagem do caminho com sufixo
                echo $this->caminhoAplicacao("teste/de/sufixo/");
            }
        };

        ob_start();
        $objeto->executar([]);
        $result = (string)ob_get_clean();

        $path = (string)realpath(__DIR__ . "/AppFalso/teste/de/sufixo");
        $this->assertStringContainsString($path, $result);
    }

    /** @test */
    public function obterCaminhoRaizComSufixoLimpandoBarraEsquerda(): void
    {
        $objeto = new class($this->fabricarTerminal()) extends Comando {
            protected function inicializar(): void
            {}

            protected function manipular(Argumentos $argumentos): void
            {
                // barras são ajustadas automaticamente na montagem do caminho com sufixo
                echo $this->caminhoAplicacao("/teste/de/sufixo");
            }
        };

        ob_start();
        $objeto->executar([]);
        $result = (string)ob_get_clean();

        $path = (string)realpath(__DIR__ . "/AppFalso/teste/de/sufixo");
        $this->assertStringContainsString($path, $result);
    }
}
