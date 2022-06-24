<?php

declare(strict_types=1);

namespace Freep\Console\Tests\Constraints;

class OptionHasDescription extends OptionHasShortNotation
{
    protected function methodToComparison(): string
    {
        return 'getDescription';
    }

    public function toString(): string
    {
        return "has description '$this->expected'";
    }
}
