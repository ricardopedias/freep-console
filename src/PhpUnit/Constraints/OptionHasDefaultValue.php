<?php

declare(strict_types=1);

namespace Freep\Console\PhpUnit\Constraints;

class OptionHasDefaultValue extends OptionHasShortNotation
{
    protected function methodToComparison(): string
    {
        return 'getDefaultValue';
    }

    public function toString(): string
    {
        return "has default value '$this->expected'";
    }
}
