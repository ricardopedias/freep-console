<?php

declare(strict_types=1);

namespace Testes;

use Freep\Console\Argumentos;
use Freep\Console\Interpretador;
use Freep\Console\Opcao;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class InterpretadorTeste extends TestCase
{
    /** @test */
    public function opcaoObrigatoriaComValorObrigatorio(): void
    {
        $interpretador = new Interpretador([
            new Opcao('-a', '--aaa', 'Descricao opcao 1', Opcao::OBRIGATORIA | Opcao::COM_VALOR),
        ]);

        $argumentos = $interpretador->interpretarArgumentos('-a valor');
        $this->assertInstanceOf(Argumentos::class, $argumentos);
        $this->assertSame('valor', $argumentos->opcao('-a'));
    }

    /**
     * Opções obrigatórias que já possuem um valor padrão, cumprem a própria obrigatoriedade
     * @test
     */
    public function opcaoObrigatoriaComValorPadrao(): void
    {
        $interpretador = new Interpretador([
            new Opcao('-a', '--aaa', 'Descricao opcao 1', Opcao::OBRIGATORIA | Opcao::COM_VALOR, 'valor padrao'),
        ]);

        $argumentos = $interpretador->interpretarArgumentos('-a');
        $this->assertInstanceOf(Argumentos::class, $argumentos);
        $this->assertSame('valor padrao', $argumentos->opcao('-a'));
    }

    /** @test */
    public function opcaoObrigatoriaExcecao(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Opções obrigatórias: -a|--aaa');

        $interpretador = new Interpretador([
            new Opcao('-a', '--aaa', 'Descricao opcao 1', Opcao::OBRIGATORIA | Opcao::COM_VALOR),
        ]);

        $interpretador->interpretarArgumentos('');
    }

    /** @test */
    public function opcaoBooleanaObrigatoria(): void
    {
        $interpretador = new Interpretador([
            new Opcao('-a', '--aaa', 'Descricao opcao 1', Opcao::OBRIGATORIA),
        ]);

        $argumentos = $interpretador->interpretarArgumentos('-a');
        $this->assertInstanceOf(Argumentos::class, $argumentos);
        $this->assertSame('1', $argumentos->opcao('-a'));
    }

    /** @test */
    public function opcaoBooleanaObrigatoriaComValorPadrao(): void
    {
        $interpretador = new Interpretador([
            new Opcao('-a', '--aaa', 'Descricao opcao 1', Opcao::OBRIGATORIA, "0"),
        ]);

        $argumentos = $interpretador->interpretarArgumentos('');
        $this->assertInstanceOf(Argumentos::class, $argumentos);
        $this->assertSame('0', $argumentos->opcao('-a'));

        // - - -

        $interpretador = new Interpretador([
            new Opcao('-a', '--aaa', 'Descricao opcao 1', Opcao::OBRIGATORIA, "1"),
        ]);

        $argumentos = $interpretador->interpretarArgumentos('');
        $this->assertInstanceOf(Argumentos::class, $argumentos);
        $this->assertSame('1', $argumentos->opcao('-a'));
    }

    /** @test */
    public function opcaoBooleanaObrigatoriaExcecao(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Opções obrigatórias: -a|--aaa');

        $interpretador = new Interpretador([
            new Opcao('-a', '--aaa', 'Descricao opcao 1', Opcao::OBRIGATORIA),
        ]);

        $interpretador->interpretarArgumentos('');
    }

    /** @test */
    public function valoresCompostos(): void
    {
        $interpretador = new Interpretador([
            new Opcao('-a', '--aaa', 'Descricao opcao 1', Opcao::OPCIONAL | Opcao::COM_VALOR),
        ]);

        $argumentos = $interpretador->interpretarArgumentos('-a "Ricardo Pereira"');
        $this->assertInstanceOf(Argumentos::class, $argumentos);
        $this->assertSame('Ricardo Pereira', $argumentos->opcao('-a'));
    }

    /** @test */
    public function valoresSemChave(): void
    {
        $interpretador = new Interpretador([
            new Opcao('-a', '--aaa', 'Descricao opcao 1', Opcao::OPCIONAL),
        ]);

        $argumentos = $interpretador->interpretarArgumentos('"Ricardo Pereira"');
        $this->assertInstanceOf(Argumentos::class, $argumentos);
        $this->assertSame('Ricardo Pereira', $argumentos->argumento(0));
    }

    /** @test */
    public function interpretacaoComplexa(): void
    {
        $interpretador = new Interpretador([
            new Opcao('-a', '--aaa', 'Descricao opcao 1', Opcao::OPCIONAL | Opcao::COM_VALOR),
            new Opcao('-b', '--bbb', 'Descricao opcao 2', Opcao::OBRIGATORIA | Opcao::COM_VALOR),
            new Opcao('-c', '--ccc', 'Descricao opcao 3', Opcao::OBRIGATORIA),
            new Opcao('-d', '--ddd', 'Descricao opcao 4', Opcao::OPCIONAL),
        ]);

        $argumentos = $interpretador->interpretarArgumentos('DDD -b "Ricardo Pereira" XX -c "Arquitetura Limpa" Teste \'Portas e Adaptadores\'');
        $this->assertInstanceOf(Argumentos::class, $argumentos);
        $this->assertSame(null, $argumentos->opcao('-a'));
        $this->assertSame('Ricardo Pereira', $argumentos->opcao('-b'));
        $this->assertSame('1', $argumentos->opcao('-c'));
        $this->assertSame('0', $argumentos->opcao('-d'));
        $this->assertSame('DDD', $argumentos->argumento(0));
        $this->assertSame('XX', $argumentos->argumento(1));
        $this->assertSame('Arquitetura Limpa', $argumentos->argumento(2));
        $this->assertSame('Teste', $argumentos->argumento(3));
        $this->assertSame('Portas e Adaptadores', $argumentos->argumento(4));
    }
}
