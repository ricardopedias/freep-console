<?php

declare(strict_types=1);

namespace Freep\Console\PhpUnit\Constraints;

use Freep\Console\Option;

class OptionIsNotRequired extends OptionIsRequired
{
    /**
     * Avalia a restrição para o argumento $other.
     * @param Option $other
     */
    protected function matches($other): bool
    {
        return parent::matches($other) === false;
    }

    public function toString(): string
    {
        return "is not required";
    }
}
