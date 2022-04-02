<?php

declare(strict_types=1);

namespace Testes;

use Freep\Console\Opcao;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class OpcaoTeste extends TestCase
{
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
}
