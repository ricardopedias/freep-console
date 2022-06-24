<?php

declare(strict_types=1);

namespace Tests\TestConstraints;

use Freep\Console\Option;
use Freep\Console\Tests\Constraints\OptionHasLongNotation;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestFailure;

class OptionHasLongNotationTest extends ConstraintTestCase
{
    /** @test */
    public function doAssertSuccess(): void
    {
        $constraint = new OptionHasLongNotation('--aaa');

        $this->assertTrue(
            $constraint->evaluate($this->optionFactory(), '', true)
        );
    }

    public function failsProvider(): array
    {
        $list = [];

        $list['fail'] = [ '--bbb', $this->optionFactory()];
        $list['success with invalid option'] = [ '--aaa', 'invalid option'];
        $list['fail with invalid option'] = [ '--bbb', 'invalid option'];

        return $list;
    }

    /**
     * @test
     * @dataProvider failsProvider
     */
    public function doAssertSuccessWithInvalidOption(string $notation, Option|string $option): void
    {
        $constraint = new OptionHasLongNotation($notation);

        try {
            $constraint->evaluate($option, '', false);
        } catch (ExpectationFailedException  $e) {
            $this->assertEquals(
                "Failed asserting that an option has long notation '{$notation}'.\n",
                $this->exceptionToString($e)
            );
        }
    }
}
