<?php

declare(strict_types=1);

namespace Tests;

use Freep\Console\Option;
use InvalidArgumentException;

class OptionTest extends TestCase
{
    /** @test */
    public function optionInfo(): void
    {
        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::REQUIRED, "1");
        $this->assertOptionHasShortNotation("-a", $opcao);
        $this->assertOptionHasLongNotation("--aaa", $opcao);
        $this->assertOptionHasDescription("Descricao opcao 1", $opcao);
    }

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
        $this->assertOptionHasDefaultValue("1", $opcao);

        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::REQUIRED, "0");
        $this->assertOptionHasDefaultValue("0", $opcao);
    }

    /** @test */
    public function defaultValue(): void
    {
        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::REQUIRED | Option::VALUED, "valor teste");
        $this->assertOptionHasDefaultValue("valor teste", $opcao);
    }

    /** @test */
    public function requireds(): void
    {
        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::REQUIRED);
        $this->assertOptionIsRequired($opcao);
        $this->assertOptionIsBoolean($opcao);
        $this->assertOptionIsNotValued($opcao);

        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::REQUIRED | Option::VALUED);
        $this->assertOptionIsRequired($opcao);
        $this->assertOptionIsNotBoolean($opcao);
        $this->assertOptionIsValued($opcao);
    }

    /** @test */
    public function optionals(): void
    {
        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::OPTIONAL);
        $this->assertOptionIsNotRequired($opcao);
        $this->assertOptionIsBoolean($opcao);
        $this->assertOptionIsNotValued($opcao);

        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::OPTIONAL | Option::VALUED);
        $this->assertOptionIsNotRequired($opcao);
        $this->assertOptionIsNotBoolean($opcao);
        $this->assertOptionIsValued($opcao);
    }
}
