<?php

declare(strict_types=1);

namespace Freep\Console\PhpUnit\Constraints;

use Freep\Console\Command;
use PHPUnit\Framework\Constraint\Constraint;

class CommandHasName extends Constraint
{
    protected string $expected;

    /** @param string $expected */
    public function __construct(string $expected)
    {
        $this->expected = $expected;
    }

    protected function methodToComparison(): string
    {
        return 'getName';
    }

    /**
     * Avalia a restrição para o argumento $other.
     * @param Command $other
     */
    protected function matches($other): bool
    {
        if (! $other instanceof Command) {
            return false;
        }

        $method = $this->methodToComparison();

        return $other->{$method}() === $this->expected;
    }

    /**
     * @param Command $other
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function failureDescription($other): string
    {
        return 'an command ' . $this->toString();
    }

    public function toString(): string
    {
        return "has name '$this->expected'";
    }
}
