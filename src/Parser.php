<?php

declare(strict_types=1);

namespace Freep\Console;

use RuntimeException;

class Parser
{
    /**
     * Mapeamento de notações curtas/longas para notação principal
     * @var array<string,string>
     */
    private array $notationMap = [];

    /**
     * Lista de opções identificáveis pela notação principal
     * @var array<string,Option> */
    private array $configuredOptions = [];

    /**
     * Lista de valores identificáveis pela notação principal
     * @var array<string,mixed> */
    private array $flaggedOptions = [];

    /**
     * Lista de textos especificados que não pertencem a nenhuma opção
     * @var array<int,string> */
    private array $standAloneOptions = [];

    /** @param array<int,Option> $configuration */
    public function __construct(array $configuration)
    {
        /** @var Option $option */
        foreach ($configuration as $option) {
            $mainNotation = $option->getMainNotation();
            $shortNotation = $option->getShortNotation();
            $longNotation = $option->getLongNotation();

            $this->configuredOptions[$mainNotation] = $option;
            $this->notationMap[$shortNotation] = $mainNotation;
            $this->notationMap[$longNotation] = $mainNotation;
        }
    }

    private function composeValuesBetweenQuotes(): void
    {
        $this->standAloneOptions = (new Composition($this->standAloneOptions))->valores();
    }

    /**
     * @param array<int,string> $argumentList
     * @param int $index O indice da opção inválida
     */
    private function purgeInvalidOption(array &$argumentList, int $index): void
    {
        unset($argumentList[$index]);
    }

    /**
     * @param array<int,string> $argumentList
     * @param int $index O indice da opção avulsa
     */
    private function extractStandAloneOption(array &$argumentList, int $index): void
    {
        $this->standAloneOptions[] = $argumentList[$index];

        unset($argumentList[$index]);
    }

    /**
     * @param array<int,string> $argumentList
     * @param int $index O indice da opção
     */
    private function extractOption(array &$argumentList, int $index): void
    {
        $notation = array_shift($argumentList);
        $option = $this->getOption((string)$notation);
        $mainNotation = $option->getMainNotation();

        if ($option->isBoolean() === true || $option->isValued() === false) {
            $this->flaggedOptions[$mainNotation] = '1';
            return;
        }

        $compositeValue = [];
        foreach ($argumentList as $index => $argument) {
            // apenas valores são agrupados aqui
            if ($this->isNotationFormat($argument) === true) {
                break;
            }

            $lastValueNode = $this->isClosingArgument($argument);

            $compositeValue[] = $this->removeQuotes($argument);

            // remove o argumento da lista
            unset($argumentList[$index]);

            if ($lastValueNode === true) {
                break;
            }
        }

        if ($compositeValue === [] && $option->getDefaultValue() !== "") {
            $compositeValue[] = $option->getDefaultValue();
        }

        if ($compositeValue === []) {
            throw new RuntimeException("The '{$notation}' option requires a value");
        }

        $this->flaggedOptions[$mainNotation] = implode(" ", $compositeValue);
    }

    private function removeQuotes(string $argument): string
    {
        return trim(trim($argument, "'"), '"');
    }

    private function isClosingArgument(string $argument): bool
    {
        return str_ends_with($argument, "'") || str_ends_with($argument, '"');
    }

    private function isNotationFormat(string $argument): bool
    {
        return str_starts_with($argument, "-");
    }

    private function isValidNotation(string $argument): bool
    {
        return isset($this->notationMap[$argument]);
    }

    /** @param array<int,string> $argumentList */
    private function currentIndex(array $argumentList): int
    {
        $chaves = array_keys($argumentList);
        return $chaves[0] ?? -1;
    }

    private function getOption(string $notation): Option
    {
        $notation = $this->notationMap[$notation];
        return $this->configuredOptions[$notation];
    }

    public function parseStringArguments(string $argumentsDoTerminal): Arguments
    {
        return $this->parseArgumentList(explode(' ', $argumentsDoTerminal));
    }

    /** @param array<int,string> $argumentList */
    public function parseArgumentList(array $argumentList): Arguments
    {
        while (($index = $this->currentIndex($argumentList)) !== -1) {
            $noh = $argumentList[$index];

            // texto avulso, sem chave especificada
            if ($this->isNotationFormat($noh) === false) {
                $this->extractStandAloneOption($argumentList, $index);
                continue;
            }

            // chave inválida de opção
            if ($this->isValidNotation($argumentList[$index]) === false) {
                $this->purgeInvalidOption($argumentList, $index);
                continue;
            }

            $this->extractOption($argumentList, $index);
        }

        $this->populateDefaultValues();
        $this->verifyRequireds();
        $this->populateBooleanValues();
        $this->composeValuesBetweenQuotes();

        return new Arguments($this->notationMap, $this->flaggedOptions, $this->standAloneOptions);
    }

    private function populateDefaultValues(): void
    {
        /** @var Option $option */
        foreach ($this->configuredOptions as $option) {
            if ($option->getDefaultValue() === "") {
                continue;
            }

            if (isset($this->flaggedOptions[$option->getMainNotation()]) === true) {
                continue;
            }

            $this->flaggedOptions[$option->getMainNotation()] = $option->getDefaultValue();
        }
    }

    private function populateBooleanValues(): void
    {
        /** @var Option $option */
        foreach ($this->configuredOptions as $option) {
            if ($option->isBoolean() === false) {
                continue;
            }

            if (isset($this->flaggedOptions[$option->getMainNotation()]) === true) {
                continue;
            }

            $this->flaggedOptions[$option->getMainNotation()] = '0';
        }
    }

    private function verifyRequireds(): void
    {
        $requireds = [];

        /** @var Option $option */
        foreach ($this->configuredOptions as $option) {
            if ($option->isRequired() === false) {
                continue;
            }

            if (isset($this->flaggedOptions[$option->getMainNotation()]) === true) {
                continue;
            }

            $requireds[] = $option->getShortNotation() . "|" . $option->getLongNotation();
        }

        if ($requireds === []) {
            return;
        }

        $tip = implode(", ", $requireds);

        throw new RuntimeException(sprintf("Required options: %s", $tip));
    }
}
