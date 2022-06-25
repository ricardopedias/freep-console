<?php

declare(strict_types=1);

namespace Tests\PhpUnit;

use Freep\Console\Option;
use Freep\Console\PhpUnit\Constraints\OptionHasDescription;
use PHPUnit\Framework\ExpectationFailedException;

class OptionHasDescriptionTest extends ConstraintTestCase
{
    /** @test */
    public function doAssertSuccess(): void
    {
        $constraint = new OptionHasDescription('Descricao da opcao');

        $this->assertTrue(
            $constraint->evaluate($this->optionFactory(), '', true)
        );
    }

    /** @return array<string,array<int,mixed>> */
    public function failsProvider(): array
    {
        $list = [];

        $list['fail'] = [ 'Descricao invalida', $this->optionFactory()];
        $list['success with invalid option'] = [ 'Descricao da opcao', 'invalid option'];
        $list['fail with invalid option'] = [ 'Descricao invalida', 'invalid option'];

        return $list;
    }

    /**
     * @test
     * @dataProvider failsProvider
     */
    public function doAssertSuccessWithInvalidOption(string $notation, Option|string $option): void
    {
        $constraint = new OptionHasDescription($notation);

        try {
            $constraint->evaluate($option, '', false);
        } catch (ExpectationFailedException  $e) {
            $this->assertEquals(
                "Failed asserting that an option has description '{$notation}'.\n",
                $this->exceptionToString($e)
            );
        }
    }
}
