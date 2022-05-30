<?php

declare(strict_types=1);

namespace Tests;

use Freep\Console\Arguments;
use Freep\Console\Parser;
use Freep\Console\Option;
use RuntimeException;

/** @SuppressWarnings(PHPMD.TooManyPublicMethods) */
class ParserTest extends TestCase
{
    /** @test */
    public function parseInvalidOption(): void
    {
        $interpretador = new Parser([
            new Option('-a', '--aaa', 'Descricao opcao 1', Option::REQUIRED | Option::VALUED),
        ]);

        $argumentos = $interpretador->parseStringArguments('-a valor -b valor');
        $this->assertInstanceOf(Arguments::class, $argumentos);
        $this->assertSame([
            '-a' => 'valor'
        ], $argumentos->getOptionList());
    }

    /** @test */
    public function requiredOptionWithRequiredValue(): void
    {
        $interpretador = new Parser([
            new Option('-a', '--aaa', 'Descricao opcao 1', Option::REQUIRED | Option::VALUED),
        ]);

        $argumentos = $interpretador->parseStringArguments('-a valor');
        $this->assertInstanceOf(Arguments::class, $argumentos);
        $this->assertSame('valor', $argumentos->getOption('-a'));
        $this->assertSame([
            '-a' => 'valor'
        ], $argumentos->getOptionList());
    }

    /** @test */
    public function requiredOptionValueException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("The '-a' option requires a value");

        $interpretador = new Parser([
            new Option('-a', '--aaa', 'Descricao opcao 1', Option::REQUIRED | Option::VALUED),
        ]);

        $interpretador->parseStringArguments('-a');
    }

    /**
     * Opções obrigatórias que já possuem um valor padrão, cumprem a própria obrigatoriedade
     * @test
     */
    public function requiredOptionWithDefaultValue(): void
    {
        $interpretador = new Parser([
            new Option('-a', '--aaa', 'Descricao opcao 1', Option::REQUIRED | Option::VALUED, 'valor padrao'),
        ]);

        $argumentos = $interpretador->parseStringArguments('-a');
        $this->assertInstanceOf(Arguments::class, $argumentos);
        $this->assertSame('valor padrao', $argumentos->getOption('-a'));
    }

    /** @test */
    public function requiredOptionException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Required options: -a|--aaa');

        $interpretador = new Parser([
            new Option('-a', '--aaa', 'Descricao opcao 1', Option::REQUIRED | Option::VALUED),
        ]);

        $interpretador->parseStringArguments('');
    }

    /** @test */
    public function requiredBooleanOption(): void
    {
        $interpretador = new Parser([
            new Option('-a', '--aaa', 'Descricao opcao 1', Option::REQUIRED),
        ]);

        $argumentos = $interpretador->parseStringArguments('-a');
        $this->assertInstanceOf(Arguments::class, $argumentos);
        $this->assertSame('1', $argumentos->getOption('-a'));
    }

    /** @test */
    public function requiredBooleanOptionWithDefaultValue(): void
    {
        $interpretador = new Parser([
            new Option('-a', '--aaa', 'Descricao opcao 1', Option::REQUIRED, "0"),
        ]);

        $argumentos = $interpretador->parseStringArguments('');
        $this->assertInstanceOf(Arguments::class, $argumentos);
        $this->assertSame('0', $argumentos->getOption('-a'));

        // - - -

        $interpretador = new Parser([
            new Option('-a', '--aaa', 'Descricao opcao 1', Option::REQUIRED, "1"),
        ]);

        $argumentos = $interpretador->parseStringArguments('');
        $this->assertInstanceOf(Arguments::class, $argumentos);
        $this->assertSame('1', $argumentos->getOption('-a'));
    }

    /** @test */
    public function requiredBooleanOptionException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Required options: -a|--aaa');

        $interpretador = new Parser([
            new Option('-a', '--aaa', 'Descricao opcao 1', Option::REQUIRED),
        ]);

        $interpretador->parseStringArguments('');
    }

    /** @test */
    public function compositeValues(): void
    {
        $interpretador = new Parser([
            new Option('-a', '--aaa', 'Descricao opcao 1', Option::OPTIONAL | Option::VALUED),
        ]);

        $argumentos = $interpretador->parseStringArguments('-a "Ricardo Pereira"');
        $this->assertInstanceOf(Arguments::class, $argumentos);
        $this->assertSame('Ricardo Pereira', $argumentos->getOption('-a'));
    }

    /** @test */
    public function standAloneValues(): void
    {
        $interpretador = new Parser([
            new Option('-a', '--aaa', 'Descricao opcao 1', Option::OPTIONAL),
        ]);

        $argumentos = $interpretador->parseStringArguments('"Ricardo Pereira"');
        $this->assertInstanceOf(Arguments::class, $argumentos);
        $this->assertSame('Ricardo Pereira', $argumentos->getArgument(0));
    }

    /** @test */
    public function complexParse(): void
    {
        $interpretador = new Parser([
            new Option('-a', '--aaa', 'Descricao opcao 1', Option::OPTIONAL | Option::VALUED),
            new Option('-b', '--bbb', 'Descricao opcao 2', Option::REQUIRED | Option::VALUED),
            new Option('-c', '--ccc', 'Descricao opcao 3', Option::REQUIRED),
            new Option('-d', '--ddd', 'Descricao opcao 4', Option::OPTIONAL),
        ]);

        $argumentos = $interpretador->parseStringArguments(
            'DDD -b "Ricardo Pereira" XX -c "Arquitetura Limpa" Teste \'Portas e Adaptadores\''
        );
        $this->assertInstanceOf(Arguments::class, $argumentos);
        $this->assertSame("", $argumentos->getOption('-a'));
        $this->assertSame('Ricardo Pereira', $argumentos->getOption('-b'));
        $this->assertSame('1', $argumentos->getOption('-c'));
        $this->assertSame('0', $argumentos->getOption('-d'));
        $this->assertSame('DDD', $argumentos->getArgument(0));
        $this->assertSame('XX', $argumentos->getArgument(1));
        $this->assertSame('Arquitetura Limpa', $argumentos->getArgument(2));
        $this->assertSame('Teste', $argumentos->getArgument(3));
        $this->assertSame('Portas e Adaptadores', $argumentos->getArgument(4));
    }
}
