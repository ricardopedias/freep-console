<?php

declare(strict_types=1);

namespace Tests;

use Freep\Console\Option;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class OptionNotationsTest extends TestCase
{
    /** @test */
    public function getShortNotation(): void
    {
        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::REQUIRED);
        $this->assertEquals("-a", $opcao->getShortNotation());

        $opcao = new Option(null, "--aaa", 'Descricao opcao 1', Option::REQUIRED);
        $this->assertEquals("--aaa", $opcao->getShortNotation());

        $opcao = new Option("-a", null, 'Descricao opcao 1', Option::REQUIRED);
        $this->assertEquals("-a", $opcao->getShortNotation());
    }

    /** @test */
    public function getLongNotation(): void
    {
        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::REQUIRED);
        $this->assertEquals("--aaa", $opcao->getLongNotation());

        $opcao = new Option(null, "--aaa", 'Descricao opcao 1', Option::REQUIRED);
        $this->assertEquals("--aaa", $opcao->getLongNotation());

        $opcao = new Option("-a", null, 'Descricao opcao 1', Option::REQUIRED);
        $this->assertEquals("-a", $opcao->getLongNotation());
    }

    /** @test */
    public function getMainNotation(): void
    {
        $opcao = new Option("-a", "--aaa", 'Descricao opcao 1', Option::REQUIRED);
        $this->assertEquals("-a", $opcao->getMainNotation());

        $opcao = new Option(null, "--aaa", 'Descricao opcao 1', Option::REQUIRED);
        $this->assertEquals("--aaa", $opcao->getMainNotation());

        $opcao = new Option("-a", null, 'Descricao opcao 1', Option::REQUIRED);
        $this->assertEquals("-a", $opcao->getMainNotation());
    }

    /** @test */
    public function shortNotationWithoutDashException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The short notation must start with a dash");

        new Option("a", "--aaa", 'Descricao opcao 1', Option::OPTIONAL);
    }

    /** @test */
    public function shortNotationWithTwoDashesException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The short notation must start with a dash");

        new Option("--a", "--aaa", 'Descricao opcao 1', Option::OPTIONAL);
    }

    /** @test */
    public function longNotationWithoutDashException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The long notation must start with two dashes");

        new Option("-a", "aaa", 'Descricao opcao 1', Option::OPTIONAL);
    }

    /** @test */
    public function longNotationWithOneDashException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The long notation must start with two dashes");

        new Option("-a", "-aaa", 'Descricao opcao 1', Option::OPTIONAL);
    }

    /** @test */
    public function notationsNotProvidedException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("It is mandatory to provide at least one notation");

        new Option(null, null, 'Descricao opcao 1', Option::OPTIONAL);
    }
}
