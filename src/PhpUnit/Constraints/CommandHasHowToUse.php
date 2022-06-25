<?php

declare(strict_types=1);

namespace Freep\Console\PhpUnit\Constraints;

class CommandHasHowToUse extends CommandHasName
{
    protected function methodToComparison(): string
    {
        return 'getHowToUse';
    }

    public function toString(): string
    {
        return "has how to use '$this->expected'";
    }
}
