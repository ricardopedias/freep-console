<?php

declare(strict_types=1);

namespace Freep\Console\Tests\Constraints;

class OptionHasLongNotation extends OptionHasShortNotation
{
    protected function methodToComparison(): string
    {
        return 'getLongNotation';
    }

    public function toString(): string
    {
        return "has long notation '$this->expected'";
    }
}
