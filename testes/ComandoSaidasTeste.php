<?php

declare(strict_types=1);

namespace Test\Console;

use Freep\Console\Argumentos;
use Freep\Console\Comando;
use Freep\Console\Terminal;
use PHPUnit\Framework\TestCase;

class ComandoSaidasTeste extends TestCase
{
    private function fabricarTerminal(): Terminal
    {
        return new Terminal(__DIR__ . "/AppFalso");
    }

    /** @test */
    public function execucaoComDisparoDeTexto(): void
    {
        $objeto = new class($this->fabricarTerminal()) extends Comando {
            protected function inicializar(): void
            {}

            protected function manipular(Argumentos $argumentos): void
            {
                $this->linha("exibida mensagem de texto");
            }
        };

        ob_start();
        $objeto->executar([]);
        $result = (string)ob_get_clean();

        $this->assertStringContainsString("exibida mensagem de texto", $result);
    }

    /** @test */
    public function execucaoComDisparoDeErro(): void
    {
        $objeto = new class($this->fabricarTerminal()) extends Comando {
            protected function inicializar(): void
            {}

            protected function manipular(Argumentos $argumentos): void
            {
                $this->erro("exibida mensagem de erro");
            }
        };

        ob_start();
        $objeto->executar([]);
        $result = (string)ob_get_clean();

        $this->assertStringContainsString("exibida mensagem de erro", $result);
    }

    /** @test */
    public function execucaoComDisparoDeInformacao(): void
    {
        $objeto = new class($this->fabricarTerminal()) extends Comando {
            protected function inicializar(): void
            {}

            protected function manipular(Argumentos $argumentos): void
            {
                $this->info("exibida mensagem informativa");
            }
        };

        ob_start();
        $objeto->executar([]);
        $result = (string)ob_get_clean();

        $this->assertStringContainsString("exibida mensagem informativa", $result);
    }

    /** @test */
    public function execucaoComDisparoDeAlerta(): void
    {
        $objeto = new class($this->fabricarTerminal()) extends Comando {
            protected function inicializar(): void
            {}

            protected function manipular(Argumentos $argumentos): void
            {
                $this->alerta("exibida mensagem de alerta");
            }
        };

        ob_start();
        $objeto->executar([]);
        $result = (string)ob_get_clean();

        $this->assertStringContainsString("exibida mensagem de alerta", $result);
    }
}
