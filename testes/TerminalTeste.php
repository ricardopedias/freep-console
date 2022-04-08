<?php

declare(strict_types=1);

namespace Testes;

use Freep\Console\Terminal;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Testes\AppFalso\ContextoDois\ExemploDois;
use Testes\AppFalso\ContextoUm\src\Comandos\ExemploUm;

class TerminalTeste extends TestCase
{
    /** @return array<int,string> */
    private function linhasMensagemAjuda(): array
    {
        return [
            "Modo de usar:",
            "./superapp comando [opcoes] [argumentos]",

            "Opções:",
            "-a, --ajuda",
            "Exibe as informações de ajuda",

            "Comandos disponíveis:",
            "ajuda",
            "Exibe as informações de ajuda",
            "exemplo-excecao",
            "Executa o comando exemplo-excecao",
            "exemplo1",
            "Executa o comando exemplo1",
            "exemplo2",
            "Executa o comando exemplo2"
        ];
    }

    private function fabricarTerminal(): Terminal
    {
        $terminal = new Terminal(__DIR__ . "/AppFalso");
        $terminal->setarModoDeUsar("./superapp comando [opcoes] [argumentos]");
        $terminal->carregarComandosDe(__DIR__ . "/AppFalso/ContextoUm/src/Comandos");
        $terminal->carregarComandosDe(__DIR__ . "/AppFalso/ContextoDois");
        return $terminal;
    }

    /** @test */
    public function excecaoAoCarregarComandosDe(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $terminal = new Terminal(__DIR__ . "/AppFalso");
        $terminal->carregarComandosDe("/caminho-inexistente");
    }

    /** @test */
    public function semArgumentos(): void
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->executar([]);
        $result = (string)ob_get_clean();

        $this->assertEquals("nao", $terminal->comandoExecutado());
        $this->assertEmpty($result);
    }

    /** @test */
    public function comandoInexistente(): void
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->executar([ "blabla" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals("nao", $terminal->comandoExecutado());

        foreach ($this->linhasMensagemAjuda() as $texto) {
            $this->assertStringContainsString($texto, $result);
        }
    }

    /** @test */
    public function comandoExemploUm(): void
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->executar([ "exemplo1" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals(ExemploUm::class, $terminal->comandoExecutado());
        $this->assertStringContainsString("exemplo1 executado", $result);
    }

    /** @test */
    public function comandoExemploDois(): void
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->executar([ "exemplo2" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals(ExemploDois::class, $terminal->comandoExecutado());
        $this->assertStringContainsString("exemplo2 executado", $result);
    }

    /** @test */
    public function comandoExemploExcecao(): void
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->executar([ "exemplo-excecao" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals("nao", $terminal->comandoExecutado());
        $this->assertStringContainsString("exemplo-excecao lançou exceção", $result);
    }

    /** @test */
    public function comandoExemploMalImplementado(): void
    {
        ob_start();
        $terminal = $this->fabricarTerminal();
        $terminal->carregarComandosDe(__DIR__ . "/AppFalso/ContextoTres");
        $terminal->executar([ "bug" ]);
        $result = (string)ob_get_clean();

        $this->assertEquals("nao", $terminal->comandoExecutado());
        $this->assertStringContainsString("Não é possível extrair o namespace do arquivo", $result);
    }
}
