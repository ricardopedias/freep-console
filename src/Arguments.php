<?php

declare(strict_types=1);

namespace Freep\Console;

use OutOfRangeException;

class Arguments
{
    /**
     * @param array<string,string> $notationMap Mapa de notações curtas/longas com a notação principal
     * @param array<string,mixed> $withFlag Valores identificáveis pela notação principal
     * @param array<int,string> $standAlone Valores que não pertencem a nenhuma opção
     */
    public function __construct(
        private array $notationMap,
        private array $withFlag,
        private array $standAlone
    ) {
    }

    public function getArgument(int $position): ?string
    {
        return $this->standAlone[$position] ?? null;
    }

    /** @return array<int,string> */
    public function getArgumentList(): array
    {
        return $this->standAlone;
    }

    /** @return array<string,Option> */
    public function getOptionList(): array
    {
        return $this->withFlag;
    }

    /** @return string */
    public function getOption(string $notation): string
    {
        if (isset($this->notationMap[$notation]) === false) {
            throw new OutOfRangeException("A opção '{$notation}' é inválida");
        }

        $mainNotation = $this->notationMap[$notation];
        return $this->withFlag[$mainNotation] ?? "";
    }
}
