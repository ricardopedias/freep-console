<?php

declare(strict_types=1);

namespace Freep\Console;

class Composicao
{
    private bool $emComposicao = false;

    /** @var array<int,string> */
    private array $valorComposto = [];

    /** @param array<int,string> $opcoesSemChave */
    public function __construct(private array $opcoesSemChave)
    {
    }

    private function aberturaComAspasDuplas(string $argumento): bool
    {
        return str_starts_with($argumento, '"');
    }

    private function aberturaComAspasSimples(string $argumento): bool
    {
        return str_starts_with($argumento, "'");
    }

    private function argumentoDeAbertura(string $argumento): bool
    {
        return $this->aberturaComAspasDuplas($argumento)
            || $this->aberturaComAspasSimples($argumento);
    }

    private function argumentoDeFechamento(string $argumento): bool
    {
        return $this->fechamentoComAspasDuplas($argumento)
            || $this->fechamentoComAspasSimples($argumento);
    }

    private function compondo(): bool
    {
        return $this->emComposicao;
    }

    private function comporValor(string $argumentoParcial): void
    {
        $this->emComposicao = true;
        $this->valorComposto[] = $argumentoParcial;
    }

    private function fechamentoComAspasDuplas(string $argumento): bool
    {
        return str_ends_with($argumento, '"');
    }

    private function fechamentoComAspasSimples(string $argumento): bool
    {
        return str_ends_with($argumento, "'");
    }

    private function valorComposto(): string
    {
        $valor = implode(" ", $this->valorComposto);

        $this->emComposicao = false;
        $this->valorComposto = [];

        return $this->removerAspas($valor);
    }

    private function removerAspas(string $argumento): string
    {
        return trim(trim($argumento, "'"), '"');
    }

    /** @return array<int,string> */
    public function valores(): array
    {
        $valores = [];

        foreach ($this->opcoesSemChave as $argumento) {
            if ($this->argumentoDeAbertura($argumento) === true) {
                $this->comporValor($argumento);
                continue;
            }

            if ($this->argumentoDeFechamento($argumento) === true) {
                $this->comporValor($argumento);
                $valores[] = $this->valorComposto();
                continue;
            }

            if ($this->compondo() === true) {
                $this->comporValor($argumento);
                continue;
            }

            $valores[] = $argumento;
        }

        return $valores;
    }
}
