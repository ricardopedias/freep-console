<?php

declare(strict_types=1);

namespace Freep\Console\PhpUnit\Constraints;

class OptionIsValued extends OptionIsRequired
{
    protected function methodToComparison(): string
    {
        return 'isValued';
    }

    public function toString(): string
    {
        return "is valued";
    }
}
