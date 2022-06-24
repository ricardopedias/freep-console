<?php

declare(strict_types=1);

namespace Freep\Console\Tests\Constraints;

use Freep\Console\Command;
use PHPUnit\Framework\Constraint\Constraint;

class CommandCountOptions extends Constraint
{
    protected int $expectedCount;

    /** @param int $expectedCount */
    public function __construct(int $expectedCount)
    {
        $this->expectedCount = $expectedCount;
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

        return count($other->getOptions()) === $this->expectedCount;
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
        return sprintf('options count matches %d', $this->expectedCount);
    }
}
