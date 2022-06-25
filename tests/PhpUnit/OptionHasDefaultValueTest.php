<?php

declare(strict_types=1);

namespace Tests\PhpUnit;

use Freep\Console\Option;
use Freep\Console\PhpUnit\Constraints\OptionHasDefaultValue;
use PHPUnit\Framework\ExpectationFailedException;

class OptionHasDefaultValueTest extends ConstraintTestCase
{
    /** @test */
    public function doAssertSuccess(): void
    {
        $constraint = new OptionHasDefaultValue('has value');

        $this->assertTrue(
            $constraint->evaluate($this->optionFactory(null, 'has value'), '', true)
        );
    }

    /** @return array<string,array<int,mixed>> */
    public function failsProvider(): array
    {
        $list = [];

        $list['fail'] = [ '', $this->optionFactory(null, 'has value')];
        $list['success with invalid option'] = [ 'has value', 'invalid option'];
        $list['fail with invalid option'] = [ '', 'invalid option'];

        return $list;
    }

    /**
     * @test
     * @dataProvider failsProvider
     */
    public function doAssertSuccessWithInvalidOption(string $value, Option|string $option): void
    {
        $constraint = new OptionHasDefaultValue($value);

        try {
            $constraint->evaluate($option, '', false);
        } catch (ExpectationFailedException  $e) {
            $this->assertEquals(
                "Failed asserting that an option has default value '{$value}'.\n",
                $this->exceptionToString($e)
            );
        }
    }
}
