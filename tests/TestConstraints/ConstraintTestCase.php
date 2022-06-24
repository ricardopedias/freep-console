<?php

declare(strict_types=1);

namespace Tests\TestConstraints;

use Freep\Console\Arguments;
use Freep\Console\Command;
use Freep\Console\Option;
use PHPUnit\Framework\TestFailure;
use Tests\TestCase;
use Throwable;

class ConstraintTestCase extends TestCase
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function commandFactory(): Command
    {
        return new class ($this->terminalFactory()) extends Command {
            protected function initialize(): void
            {
                $this->setName('command-name');
                $this->setDescription('command description');
                $this->setHowToUse('how to use description');

                // Por padrao, um comando tem uma opcao '-h' pré adicioada
                $this->addOption(new Option("-a", "--aaa", 'Option description', Option::OPTIONAL));
                $this->addOption(new Option("-b", "--bbb", 'Option description', Option::OPTIONAL));
            }

            protected function handle(Arguments $arguments): void
            {
                $this->line("teste");
            }
        };
    }

    protected function optionFactory(?int $type = null, ?string $defaultValue = null): Option
    {
        return new Option(
            "-a",
            "--aaa",
            'Descricao da opcao',
            $type ?? Option::REQUIRED | Option::VALUED,
            $defaultValue ?? null
        );
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function exceptionToString(Throwable $exception): string
    {
        return TestFailure::exceptionToString($exception);
    }
}
