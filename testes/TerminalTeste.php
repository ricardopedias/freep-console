<?php

declare(strict_types=1);

namespace Testes;

use Freep\Console\Comandos\Ajuda;
use Freep\Console\Terminal;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Testes\AppFalso\ContextoDois\ExemploDois;
use Testes\AppFalso\ContextoUm\src\Comandos\ExemploUm;

class TerminalTeste extends TestCase
{
    private function fabricarTerminal(): Terminal
    {
        $terminal = new Terminal(__DIR__ . "/AppFalso");
        $terminal->carregarComandosDe(__DIR__ . "/AppFalso/ContextoUm/src/Comandos");
        $terminal->carregarComandosDe(__DIR__ . "/AppFalso/ContextoDois");
        return $terminal;
    }

    /** @test */
    public function excecaoAoCarregarComandosDe()
    {
        $this->expectException(InvalidArgumentException::class);

        $terminal = new Terminal(__DIR__ . "/AppFalso");
        $terminal->carregarComandosDe("/caminho-inexistente");
    }

    /** @test */
    public function semArgumentos()
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->executar([]);
        $result = ob_get_clean();

        $this->assertEquals("nao", $terminal->comandoExecutado());
        $this->assertEmpty($result);
    }

    /** @test */
    public function comandoInexistente()
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->executar([ "blabla" ]);
        $result = ob_get_clean();

        $this->assertEquals("nao", $terminal->comandoExecutado());
        $this->assertStringContainsString("comando nao encontrado", $result);
    }

    /** @test */
    public function comandoExemploUm()
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->executar([ "exemplo1" ]);
        $result = ob_get_clean();

        $this->assertEquals(ExemploUm::class, $terminal->comandoExecutado());
        $this->assertStringContainsString("exemplo1 executado", $result);
    }

    /** @test */
    public function comandoExemploDois()
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->executar([ "exemplo2" ]);
        $result = ob_get_clean();
        
        $this->assertEquals(ExemploDois::class, $terminal->comandoExecutado());
        $this->assertStringContainsString("exemplo2 executado", $result);
    }

    /** @test */
    public function comandoExemploExcecao()
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->executar([ "exemplo-excecao" ]);
        $result = ob_get_clean();

        $this->assertEquals("nao", $terminal->comandoExecutado());
        $this->assertStringContainsString("exemplo-excecao lançou exceção", $result);
    }

    /** @test */
    public function comandoExemploMalImplementado()
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->carregarComandosDe(__DIR__ . "/AppFalso/ContextoTres");
        $terminal->executar([ "bug" ]);
        $result = ob_get_clean();
        
        $this->assertEquals("nao", $terminal->comandoExecutado());
        $this->assertStringContainsString("Não é possível extrair o namespace do arquivo", $result);
    }
}