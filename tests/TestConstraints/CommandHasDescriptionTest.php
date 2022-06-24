<?php

declare(strict_types=1);

namespace Tests\TestConstraints;

use Freep\Console\Command;
use Freep\Console\Tests\Constraints\CommandHasDescription;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestFailure;

class CommandHasDescriptionTest extends ConstraintTestCase
{
    /** @test */
    public function doAssertSuccess(): void
    {
        $constraint = new CommandHasDescription('command description');

        $this->assertTrue(
            $constraint->evaluate($this->commandFactory(), '', true)
        );
    }

    public function failsProvider(): array
    {
        $list = [];

        $list['fail invalid description'] = [ 'monono', $this->commandFactory()];
        $list['fail empty description'] = [ '', $this->commandFactory()];
        $list['success invalid command'] = [ 'command description', 'invalid command'];
        $list['fail invalid command'] = [ 'monomo', 'invalid command'];

        return $list;
    }

    /**
     * @test
     * @dataProvider failsProvider
     */
    public function doAssertFail(string $value, Command|string $command): void
    {
        $constraint = new CommandHasDescription($value);

        try {
            $constraint->evaluate($command, '', false);
        } catch (ExpectationFailedException  $e) {
            $this->assertEquals(
                "Failed asserting that an command has description '{$value}'.\n",
                $this->exceptionToString($e)
            );
        }
    }
}
