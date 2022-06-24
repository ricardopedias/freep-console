<?php

declare(strict_types=1);

namespace Tests\TestConstraints;

use Freep\Console\Command;
use Freep\Console\Tests\Constraints\CommandHasHowToUse;
use PHPUnit\Framework\ExpectationFailedException;

class CommandHasHowToUseTest extends ConstraintTestCase
{
    /** @test */
    public function doAssertSuccess(): void
    {
        $constraint = new CommandHasHowToUse('how to use description');

        $this->assertTrue(
            $constraint->evaluate($this->commandFactory(), '', true)
        );
    }

    /** @return array<string,array<int,mixed>> */
    public function failsProvider(): array
    {
        $list = [];

        $list['fail invalid how to use'] = [ 'monono', $this->commandFactory()];
        $list['fail empty how to use'] = [ '', $this->commandFactory()];
        $list['success invalid command'] = [ 'how to use description', 'invalid command'];
        $list['fail invalid command'] = [ 'monomo', 'invalid command'];

        return $list;
    }

    /**
     * @test
     * @dataProvider failsProvider
     */
    public function doAssertFail(string $value, Command|string $command): void
    {
        $constraint = new CommandHasHowToUse($value);

        try {
            $constraint->evaluate($command, '', false);
        } catch (ExpectationFailedException  $e) {
            $this->assertEquals(
                "Failed asserting that an command has how to use '{$value}'.\n",
                $this->exceptionToString($e)
            );
        }
    }
}
