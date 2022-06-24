<?php

declare(strict_types=1);

namespace Freep\Console\Tests\Constraints;

use Freep\Console\Option;
use PHPUnit\Framework\Constraint\Constraint;

class OptionIsRequired extends Constraint
{
    protected function methodToComparison(): string
    {
        return 'isRequired';
    }

    /**
     * Avalia a restrição para o argumento $other.
     * @param Option $other
     */
    protected function matches($other): bool
    {
        if (! $other instanceof Option) {
            return false;
        }

        $method = $this->methodToComparison();

        return $other->{$method}() === true;
    }

    /**
     * @param Option $other
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function failureDescription($other): string
    {
        return 'an option ' . $this->toString();
    }

    public function toString(): string
    {
        return "is required";
    }
}
