<?php

declare(strict_types=1);

namespace Testes;

use Freep\Console\Opcao;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class OpcaoTeste extends TestCase
{
    /** @test */
    public function notacoesCurtas(): void
    {
        $opcao = new Opcao("-a", "--aaa", 'Descricao opcao 1', Opcao::OBRIGATORIA);
        $this->assertEquals("-a", $opcao->notacaoCurta());

        $opcao = new Opcao(null, "--aaa", 'Descricao opcao 1', Opcao::OBRIGATORIA);
        $this->assertEquals("--aaa", $opcao->notacaoCurta());

        $opcao = new Opcao("-a", null, 'Descricao opcao 1', Opcao::OBRIGATORIA);
        $this->assertEquals("-a", $opcao->notacaoCurta());
    }

    /** @test */
    public function notacoesLongas(): void
    {
        $opcao = new Opcao("-a", "--aaa", 'Descricao opcao 1', Opcao::OBRIGATORIA);
        $this->assertEquals("--aaa", $opcao->notacaoLonga());

        $opcao = new Opcao(null, "--aaa", 'Descricao opcao 1', Opcao::OBRIGATORIA);
        $this->assertEquals("--aaa", $opcao->notacaoLonga());

        $opcao = new Opcao("-a", null, 'Descricao opcao 1', Opcao::OBRIGATORIA);
        $this->assertEquals("-a", $opcao->notacaoLonga());
    }

    /** @test */
    public function notacaoPrincipal(): void
    {
        $opcao = new Opcao("-a", "--aaa", 'Descricao opcao 1', Opcao::OBRIGATORIA);
        $this->assertEquals("-a", $opcao->notacaoPrincipal());

        $opcao = new Opcao(null, "--aaa", 'Descricao opcao 1', Opcao::OBRIGATORIA);
        $this->assertEquals("--aaa", $opcao->notacaoPrincipal());

        $opcao = new Opcao("-a", null, 'Descricao opcao 1', Opcao::OBRIGATORIA);
        $this->assertEquals("-a", $opcao->notacaoPrincipal());
    }

    /** @test */
    public function valorBooleanoPadraoExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Um valor booleano deve ser '0' ou '1'");

        $opcao = new Opcao("-a", "--aaa", 'Descricao opcao 1', Opcao::OBRIGATORIA, "valor teste");
        $this->assertEquals("", $opcao->valorPadrao());
    }

    /** @test */
    public function valorBooleanoPadrao(): void
    {
        $opcao = new Opcao("-a", "--aaa", 'Descricao opcao 1', Opcao::OBRIGATORIA, "1");
        $this->assertEquals("1", $opcao->valorPadrao());

        $opcao = new Opcao("-a", "--aaa", 'Descricao opcao 1', Opcao::OBRIGATORIA, "0");
        $this->assertEquals("0", $opcao->valorPadrao());
    }

    /** @test */
    public function valorPadrao(): void
    {
        $opcao = new Opcao("-a", "--aaa", 'Descricao opcao 1', Opcao::OBRIGATORIA | Opcao::COM_VALOR, "valor teste");
        $this->assertEquals("valor teste", $opcao->valorPadrao());
    }

    /** @test */
    public function obrigatorias(): void
    {
        $opcao = new Opcao("-a", "--aaa", 'Descricao opcao 1', Opcao::OBRIGATORIA);
        $this->assertTrue($opcao->obrigatoria());
        $this->assertTrue($opcao->booleana());
        $this->assertFalse($opcao->valorada());

        $opcao = new Opcao("-a", "--aaa", 'Descricao opcao 1', Opcao::OBRIGATORIA | Opcao::COM_VALOR);
        $this->assertTrue($opcao->obrigatoria());
        $this->assertFalse($opcao->booleana());
        $this->assertTrue($opcao->valorada());
    }

    /** @test */
    public function opcionais(): void
    {
        $opcao = new Opcao("-a", "--aaa", 'Descricao opcao 1', Opcao::OPCIONAL);
        $this->assertFalse($opcao->obrigatoria());
        $this->assertTrue($opcao->booleana());
        $this->assertFalse($opcao->valorada());

        $opcao = new Opcao("-a", "--aaa", 'Descricao opcao 1', Opcao::OPCIONAL | Opcao::COM_VALOR);
        $this->assertFalse($opcao->obrigatoria());
        $this->assertFalse($opcao->booleana());
        $this->assertTrue($opcao->valorada());
    }

    /** @test */
    public function excecaoNotacaoCurtaSemTraco(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("A notação curta deve iniciar com um traço");

        new Opcao("a", "--aaa", 'Descricao opcao 1', Opcao::OPCIONAL);
    }

    /** @test */
    public function excecaoNotacaoCurtaComDoisTracos(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("A notação curta deve iniciar com um traço");

        new Opcao("--a", "--aaa", 'Descricao opcao 1', Opcao::OPCIONAL);
    }

    /** @test */
    public function excecaoNotacaoLongaSemTracos(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("A notação longa deve iniciar com dois traços");

        new Opcao("-a", "aaa", 'Descricao opcao 1', Opcao::OPCIONAL);
    }

    /** @test */
    public function excecaoNotacaoLongaComUmTraco(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("A notação longa deve iniciar com dois traços");

        new Opcao("-a", "-aaa", 'Descricao opcao 1', Opcao::OPCIONAL);
    }

    /** @test */
    public function excecaoNotacoesNaoFornecidas(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("É obrigatório fornecer pelo menos uma notação");

        new Opcao(null, null, 'Descricao opcao 1', Opcao::OPCIONAL);
    }
}
