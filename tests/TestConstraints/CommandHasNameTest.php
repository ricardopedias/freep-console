<?php

declare(strict_types=1);

namespace Tests\TestConstraints;

use Freep\Console\Command;
use Freep\Console\Tests\Constraints\CommandHasName;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestFailure;

class CommandHasNameTest extends ConstraintTestCase
{
    /** @test */
    public function doAssertSuccess(): void
    {
        $constraint = new CommandHasName('command-name');

        $this->assertTrue(
            $constraint->evaluate($this->commandFactory(), '', true)
        );
    }

    public function failsProvider(): array
    {
        $list = [];

        $list['fail invalid name'] = [ 'monono', $this->commandFactory()];
        $list['fail empty name'] = [ '', $this->commandFactory()];
        $list['success invalid command'] = [ 'command-name', 'invalid command'];
        $list['fail invalid command'] = [ 'monomo', 'invalid command'];

        return $list;
    }

    /**
     * @test
     * @dataProvider failsProvider
     */
    public function doAssertFail(string $value, Command|string $command): void
    {
        $constraint = new CommandHasName($value);

        try {
            $constraint->evaluate($command, '', false);
        } catch (ExpectationFailedException  $e) {
            $this->assertEquals(
                "Failed asserting that an command has name '{$value}'.\n",
                $this->exceptionToString($e)
            );
        }
    }
}
