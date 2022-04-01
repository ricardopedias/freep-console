<?php

declare(strict_types=1);

namespace Test\Console;

use Freep\Console\Argumentos;
use Freep\Console\Comando;
use Freep\Console\Opcao;
use Freep\Console\Terminal;
use PHPUnit\Framework\TestCase;

class ComandoExecucaoTeste extends TestCase
{
    private function fabricarTerminal(): Terminal
    {
        return new Terminal(__DIR__ . "/AppFalso");
    }

    /** @test */
    public function execucao(): void
    {
        $objeto = new class($this->fabricarTerminal()) extends Comando {
            protected function inicializar(): void
            {
                $this->setarNome("teste");
                $this->setarDescricao("Descrição do comando");
                $this->adicionarOpcao(new Opcao("-a", "--aaa", 'Descricao opcao 1', Opcao::OPCIONAL));
            }

            protected function manipular(Argumentos $argumentos): void
            {
                $this->linha($this->nome());
                $this->linha($this->descricao());
                $this->linha("Total de " . count($this->opcoes()) . " opção");
                $this->linha('executado');
            }
        };

        ob_start();
        $objeto->executar([]);
        $result = (string)ob_get_clean();

        $this->assertStringContainsString("teste", $result);
        $this->assertStringContainsString("Total de 2 opção", $result);
        $this->assertStringContainsString("Descrição do comando", $result);
        $this->assertStringContainsString("executado", $result);
    }

    /** @test */
    public function execucaoDescricaoPadraoComNome(): void
    {
        $objeto = new class($this->fabricarTerminal()) extends Comando {
            protected function inicializar(): void
            {
                $this->setarNome("teste");
            }

            protected function manipular(Argumentos $argumentos): void
            {
                $this->linha($this->descricao());
            }
        };

        ob_start();
        $objeto->executar([]);
        $result = (string)ob_get_clean();

        $this->assertStringContainsString("Executar o comando 'teste'", $result);
    }

    /** @test */
    public function execucaoDescricaoPadraoSemNome(): void
    {
        $objeto = new class($this->fabricarTerminal()) extends Comando {
            protected function inicializar(): void
            {}

            protected function manipular(Argumentos $argumentos): void
            {
                $this->linha($this->descricao());
            }
        };

        ob_start();
        $objeto->executar([]);
        $result = (string)ob_get_clean();

        $this->assertStringContainsString("Executar o comando 'sem-nome'", $result);
    }
}
