<?php

declare(strict_types=1);

namespace Freep\Console\Tests\Constraints;

use Freep\Console\Command;
use PHPUnit\Framework\Constraint\Constraint;

class CommandHasOption extends Constraint
{
    protected string $expected;

    /** @param string $expected */
    public function __construct(string $expected)
    {
        $this->expected = $expected;
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

        $optionList = $other->getOptions();

        foreach ($optionList as $option) {
            if (
                $option->getShortNotation() === $this->expected
                || $option->getLongNotation() === $this->expected
            ) {
                return true;
            }
        }

        return false;
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
        return "has option '$this->expected'";
    }
}
