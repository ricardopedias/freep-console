<?php

declare(strict_types=1);

namespace Tests\TestConstraints;

use Freep\Console\Option;
use Freep\Console\Tests\Constraints\OptionIsRequired;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestFailure;

class OptionIsRequiredTest extends ConstraintTestCase
{
    public function successProvider(): array
    {
        $list = [];

        $list['required boolean'] = [ $this->optionFactory(Option::REQUIRED)];
        $list['required boolean with default false'] = [
            $this->optionFactory(Option::REQUIRED, '0')
        ];
        $list['required boolean with default true'] = [
            $this->optionFactory(Option::REQUIRED, '1')
        ];
        $list['required valued'] = [ $this->optionFactory(Option::REQUIRED | Option::VALUED) ];
        $list['required valued with default value'] = [
            $this->optionFactory(Option::REQUIRED | Option::VALUED, 'has value')
        ];

        return $list;
    }

    /**
     * @test
     * @dataProvider successProvider
     */
    public function doAssertSuccess(Option $option): void
    {
        $constraint = new OptionIsRequired();

        $this->assertTrue(
            $constraint->evaluate($option, '', true)
        );
    }

    public function failsProvider(): array
    {
        $list = [];

        $list['optional'] = [ $this->optionFactory(Option::OPTIONAL)];
        $list['optional with default false'] = [
            $this->optionFactory(Option::OPTIONAL, '0')
        ];
        $list['optional with default true'] = [
            $this->optionFactory(Option::OPTIONAL, '1')
        ];
        $list['optional valued'] = [ $this->optionFactory(Option::OPTIONAL | Option::VALUED)];
        $list['optional valued with default value'] = [
            $this->optionFactory(Option::OPTIONAL | Option::VALUED, 'has value')
        ];
        $list['valued']  = [ $this->optionFactory(Option::VALUED)];
        $list['valued with default value']  = [ $this->optionFactory(Option::VALUED, 'has value')];
        $list['invalid option'] = [ 'invalid option'];

        return $list;
    }

    /**
     * @test
     * @dataProvider failsProvider
     */
    public function doAssertFail(Option|string $option): void
    {
        $constraint = new OptionIsRequired();

        try {
            $constraint->evaluate($option, '', false);
        } catch (ExpectationFailedException  $e) {
            $this->assertEquals(
                "Failed asserting that an option is required.\n",
                $this->exceptionToString($e)
            );
        }
    }
}
