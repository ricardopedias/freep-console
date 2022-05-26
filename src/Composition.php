<?php

declare(strict_types=1);

namespace Freep\Console;

class Composition
{
    private bool $inComposition = false;

    /** @var array<int,string> */
    private array $compositeValue = [];

    /** @param array<int,string> $standAloneOptions */
    public function __construct(private array $standAloneOptions)
    {
    }

    private function containsOpeningDoubleQuotes(string $argument): bool
    {
        return str_starts_with($argument, '"');
    }

    private function containsOpeningSingleQuotes(string $argument): bool
    {
        return str_starts_with($argument, "'");
    }

    private function isOpeningArgument(string $argument): bool
    {
        return $this->containsOpeningDoubleQuotes($argument)
            || $this->containsOpeningSingleQuotes($argument);
    }

    private function isClosingArgument(string $argument): bool
    {
        return $this->containsClosingDoubleQuotes($argument)
            || $this->containsClosingSingleQuotes($argument);
    }

    private function composing(): bool
    {
        return $this->inComposition;
    }

    private function composeValue(string $parcialArgument): void
    {
        $this->inComposition = true;
        $this->compositeValue[] = $parcialArgument;
    }

    private function containsClosingDoubleQuotes(string $argument): bool
    {
        return str_ends_with($argument, '"');
    }

    private function containsClosingSingleQuotes(string $argument): bool
    {
        return str_ends_with($argument, "'");
    }

    private function compositeValue(): string
    {
        $value = implode(" ", $this->compositeValue);

        $this->inComposition = false;
        $this->compositeValue = [];

        return $this->removeQuotes($value);
    }

    private function removeQuotes(string $argument): string
    {
        return trim(trim($argument, "'"), '"');
    }

    /** @return array<int,string> */
    public function valores(): array
    {
        $valueList = [];

        foreach ($this->standAloneOptions as $argument) {
            if ($this->isOpeningArgument($argument) === true) {
                $this->composeValue($argument);
                continue;
            }

            if ($this->isClosingArgument($argument) === true) {
                $this->composeValue($argument);
                $valueList[] = $this->compositeValue();
                continue;
            }

            if ($this->composing() === true) {
                $this->composeValue($argument);
                continue;
            }

            $valueList[] = $argument;
        }

        return $valueList;
    }
}
