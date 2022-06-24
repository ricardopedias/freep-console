<?php

declare(strict_types=1);

namespace Tests\TestConstraints;

use Freep\Console\Option;
use Freep\Console\Tests\Constraints\OptionIsNotBoolean;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestFailure;

class OptionIsNotBooleanTest extends ConstraintTestCase
{
    public function successProvider(): array
    {
        $list = [];

        $list['optional valued'] = [ $this->optionFactory(Option::OPTIONAL | Option::VALUED)];
        $list['optional valued with default value'] = [
            $this->optionFactory(Option::OPTIONAL | Option::VALUED, 'valued')
        ];
        $list['required valued'] = [ $this->optionFactory(Option::REQUIRED | Option::VALUED) ];
        $list['required valued with default value'] = [
            $this->optionFactory(Option::REQUIRED | Option::VALUED, 'valued')
        ];
        $list['valued'] = [ $this->optionFactory(Option::VALUED)];
        $list['invalid option'] = [ 'invalid option'];

        return $list;
    }

    /**
     * @test
     * @dataProvider successProvider
     */
    public function doAssertSuccess(Option|string $option): void
    {
        $constraint = new OptionIsNotBoolean();

        $this->assertTrue(
            $constraint->evaluate($option, '', true)
        );
    }

    public function failsProvider(): array
    {
        $list = [];

        $list['optional boolean'] = [ $this->optionFactory(Option::OPTIONAL)];
        $list['required boolean'] = [ $this->optionFactory(Option::REQUIRED) ];

        return $list;
    }

    /**
     * @test
     * @dataProvider failsProvider
     */
    public function doAssertFail(Option $option): void
    {
        $constraint = new OptionIsNotBoolean();

        try {
            $constraint->evaluate($option, '', false);
        } catch (ExpectationFailedException  $e) {
            $this->assertEquals(
                "Failed asserting that an option is not boolean.\n",
                $this->exceptionToString($e)
            );
        }
    }
}
