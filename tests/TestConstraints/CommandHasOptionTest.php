<?php

declare(strict_types=1);

namespace Tests\TestConstraints;

use Freep\Console\Command;
use Freep\Console\Tests\Constraints\CommandHasOption;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestFailure;

class CommandHasOptionTest extends ConstraintTestCase
{
    public function successProvider(): array
    {
        $list = [];

        $list['-a'] = [ '-a' ];
        $list['--aaa'] = [ '--aaa' ];
        $list['-b'] = [ '-b' ];
        $list['--bbb'] = [ '--bbb' ];

        return $list;
    }

    /**
     * @test
     * @dataProvider successProvider
     */
    public function doAssertSuccess(string $notation): void
    {
        $constraint = new CommandHasOption($notation);

        $this->assertTrue(
            $constraint->evaluate($this->commandFactory(), '', true)
        );
    }

    public function failsProvider(): array
    {
        $list = [];

        $list['fail invalid option'] = [ '-c', $this->commandFactory()];
        $list['fail empty option'] = [ '', $this->commandFactory()];
        $list['success invalid command'] = [ '-a', 'invalid command'];
        $list['fail empty option invalid command'] = [ '', 'invalid command'];

        return $list;
    }

    /**
     * @test
     * @dataProvider failsProvider
     */
    public function doAssertFail(string $value, Command|string $command): void
    {
        $constraint = new CommandHasOption($value);

        try {
            $constraint->evaluate($command, '', false);
        } catch (ExpectationFailedException  $e) {
            $this->assertEquals(
                "Failed asserting that an command has option '{$value}'.\n",
                $this->exceptionToString($e)
            );
        }
    }
}
