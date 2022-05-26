<?php

declare(strict_types=1);

namespace Tests;

use Freep\Console\Option;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class OptionTest extends TestCase
{
    /** @test */
    public function defaultBooleanValueException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("A boolean value must be '0' or '1'");

        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::REQUIRED, "valor teste");
        $this->assertEquals("", $opcao->getDefaultValue());
    }

    /** @test */
    public function defaultBooleanValue(): void
    {
        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::REQUIRED, "1");
        $this->assertEquals("1", $opcao->getDefaultValue());

        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::REQUIRED, "0");
        $this->assertEquals("0", $opcao->getDefaultValue());
    }

    /** @test */
    public function defaultValue(): void
    {
        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::REQUIRED | Option::VALUED, "valor teste");
        $this->assertEquals("valor teste", $opcao->getDefaultValue());
    }

    /** @test */
    public function requireds(): void
    {
        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::REQUIRED);
        $this->assertTrue($opcao->isRequired());
        $this->assertTrue($opcao->isBoolean());
        $this->assertFalse($opcao->isValued());

        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::REQUIRED | Option::VALUED);
        $this->assertTrue($opcao->isRequired());
        $this->assertFalse($opcao->isBoolean());
        $this->assertTrue($opcao->isValued());
    }

    /** @test */
    public function optionals(): void
    {
        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::OPTIONAL);
        $this->assertFalse($opcao->isRequired());
        $this->assertTrue($opcao->isBoolean());
        $this->assertFalse($opcao->isValued());

        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::OPTIONAL | Option::VALUED);
        $this->assertFalse($opcao->isRequired());
        $this->assertFalse($opcao->isBoolean());
        $this->assertTrue($opcao->isValued());
    }
}
