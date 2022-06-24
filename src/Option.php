<?php

declare(strict_types=1);

namespace Freep\Console;

use InvalidArgumentException;

class Option
{
    public const OPTIONAL = 4;

    public const REQUIRED = 8;

    public const VALUED = 16;

    private string $shortNotation;

    private string $longNotation;

    private string $description = '';

    private string $defaultValue = "";

    private bool $required = false;

    private bool $valued = false;

    private bool $boolean = false;

    public function __construct(
        ?string $shortNotation,
        ?string $longNotation,
        string $description,
        int $type,
        ?string $defaultValue = null
    ) {
        if ($shortNotation === null && $longNotation === null) {
            throw new InvalidArgumentException("It is mandatory to provide at least one notation");
        }

        $this->validateShortNotation($shortNotation);
        $this->validateLongNotation($longNotation);

        $this->shortNotation = $shortNotation ?? $longNotation;
        $this->longNotation  = $longNotation ?? $shortNotation;
        $this->description   = $description;
        $this->defaultValue  = $defaultValue ?? "";

        $requiredTypes = [
            Option::REQUIRED,
            Option::REQUIRED | Option::VALUED
        ];
        if (in_array($type, $requiredTypes) === true) {
            $this->required = true;
        }

        $valuedTypes = [
            Option::VALUED,
            Option::OPTIONAL | Option::VALUED,
            Option::REQUIRED | Option::VALUED,
        ];
        if (in_array($type, $valuedTypes) === true) {
            $this->valued = true;
        }

        $booleanTypes = [
            Option::OPTIONAL,
            Option::REQUIRED
        ];

        if (in_array($type, $booleanTypes) === true) {
            $this->boolean = true;
        }

        if ($this->boolean === true && in_array($this->defaultValue, ["0", "1", ""]) === false) {
            throw new InvalidArgumentException("A boolean value must be '0' or '1'");
        }
    }

    private function validateShortNotation(?string $shortNotation): void
    {
        if (
            $shortNotation !== null
            && (str_starts_with($shortNotation, "--") === true || str_starts_with($shortNotation, "-") === false)
        ) {
            throw new InvalidArgumentException("The short notation must start with a dash");
        }
    }

    private function validateLongNotation(?string $longNotation): void
    {
        if ($longNotation !== null && str_starts_with($longNotation, "--") === false) {
            throw new InvalidArgumentException("The long notation must start with two dashes");
        }
    }

    /**
     * Devolve a notação principal da opção (short ou long)
     * Se ambas notações estiverem presentes no construtor, a mais curta será a principal.
     * Quando a mais curta for setada como nula no construtor, a longa será a principal
     */
    public function getMainNotation(): string
    {
        return $this->shortNotation ?? $this->longNotation;
    }

    public function getShortNotation(): string
    {
        return $this->shortNotation;
    }

    public function getLongNotation(): string
    {
        return $this->longNotation;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDefaultValue(): string
    {
        return $this->defaultValue;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isBoolean(): bool
    {
        return $this->boolean;
    }

    public function isValued(): bool
    {
        return $this->valued;
    }
}
