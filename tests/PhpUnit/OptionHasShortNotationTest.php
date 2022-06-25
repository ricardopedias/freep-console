<?php

declare(strict_types=1);

namespace Tests\PhpUnit;

use Freep\Console\Option;
use Freep\Console\PhpUnit\Constraints\OptionHasShortNotation;
use PHPUnit\Framework\ExpectationFailedException;

class OptionHasShortNotationTest extends ConstraintTestCase
{
    /** @test */
    public function doAssertSuccess(): void
    {
        $constraint = new OptionHasShortNotation('-a');

        $this->assertTrue(
            $constraint->evaluate($this->optionFactory(), '', true)
        );
    }

    /** @return array<string,array<int,mixed>> */
    public function failsProvider(): array
    {
        $list = [];

        $list['fail'] = [ '-b', $this->optionFactory()];
        $list['success with invalid option'] = [ '-a', 'invalid option'];
        $list['fail with invalid option'] = [ '-b', 'invalid option'];

        return $list;
    }

    /**
     * @test
     * @dataProvider failsProvider
     */
    public function doAssertSuccessWithInvalidOption(string $notation, Option|string $option): void
    {
        $constraint = new OptionHasShortNotation($notation);

        try {
            $constraint->evaluate($option, '', false);
        } catch (ExpectationFailedException  $e) {
            $this->assertEquals(
                "Failed asserting that an option has short notation '{$notation}'.\n",
                $this->exceptionToString($e)
            );
        }
    }
}
