<?php

declare(strict_types=1);

namespace Test\Console;

use Freep\Console\Argumentos;
use Freep\Console\Comando;
use Freep\Console\Opcao;
use Freep\Console\Terminal;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class ComandoImplementacoesTeste extends TestCase
{
    private function fabricarTerminal(): Terminal
    {
        return new Terminal(__DIR__ . "/AppFalso");
    }

    /** @test */
    public function implementacaoComNomeInvalido(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("O nome de um comando deve ser no formato kebab-case. Ex: nome-do-comando");

        new class ($this->fabricarTerminal()) extends Comando {
            protected function inicializar(): void
            {
                $this->setarNome('teste com espaço');
            }

            protected function manipular(Argumentos $argumentos): void
            {
            }
        };
    }

    /** @test */
    public function implementacaoComOpcao(): void
    {
        $objeto = new class ($this->fabricarTerminal()) extends Comando {
            protected function inicializar(): void
            {
                $this->setarNome('teste');
                $this->adicionarOpcao(new Opcao("-p", "--port", 'Descricao opcao 1', Opcao::OBRIGATORIA));
            }

            protected function manipular(Argumentos $argumentos): void
            {
                echo "teste";
            }
        };

        ob_start();
        $objeto->executar([ "-p", '8080' ]);
        $result = (string)ob_get_clean();
        $this->assertStringContainsString("teste", $result);
    }

    /** @test */
    public function implementacaoComOpcaoObrigatoria(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Opções obrigatórias: -p|--port');

        $objeto = new class ($this->fabricarTerminal()) extends Comando {
            protected function inicializar(): void
            {
                $this->adicionarOpcao(new Opcao("-p", "--port", 'Descricao opcao 1', Opcao::OBRIGATORIA));
            }

            protected function manipular(Argumentos $argumentos): void
            {
            }
        };

        $objeto->executar([]);
    }
}
