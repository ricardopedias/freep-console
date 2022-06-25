<?php

declare(strict_types=1);

namespace Freep\Console\PhpUnit\Constraints;

use Freep\Console\Option;
use PHPUnit\Framework\Constraint\Constraint;

class OptionHasShortNotation extends Constraint
{
    protected string $expected;

    /** @param string $expected */
    public function __construct(string $expected)
    {
        $this->expected = $expected;
    }

    protected function methodToComparison(): string
    {
        return 'getShortNotation';
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

        return $other->{$method}() === $this->expected;
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
        return "has short notation '$this->expected'";
    }
}
