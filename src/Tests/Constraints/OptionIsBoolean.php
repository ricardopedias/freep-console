<?php

declare(strict_types=1);

namespace Freep\Console\Tests\Constraints;

class OptionIsBoolean extends OptionIsRequired
{
    protected function methodToComparison(): string
    {
        return 'isBoolean';
    }

    public function toString(): string
    {
        return "is boolean";
    }
}
