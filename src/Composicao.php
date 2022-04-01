<?php

declare(strict_types=1);

namespace Freep\Console;

use RuntimeException;

class Composicao
{
    private bool $emComposicao = false;

    private array $valorComposto = [];

    /** @param array<int,string> $opcoesSemChave */
    public function __construct(private array $opcoesSemChave)
    {}

    private function aberturaAspasDuplas(string $argumento): bool
    {
        return str_starts_with($argumento, '"');
    }

    private function aberturaAspasSimples(string $argumento): bool
    {
        return str_starts_with($argumento, "'");
    }

    private function argumentoDeAbertura(string $argumento): bool
    {
        return $this->aberturaAspasDuplas($argumento)
            || $this->aberturaAspasSimples($argumento);
    }

    private function argumentoDeFechamento(string $argumento): bool
    {
        return $this->fechamentoAspasDuplas($argumento)
            || $this->fechamentoAspasSimples($argumento);
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
    
    private function fechamentoAspasDuplas(string $argumento): bool
    {
        return str_ends_with($argumento, '"');
    }

    private function fechamentoAspasSimples(string $argumento): bool
    {
        return str_ends_with($argumento, "'");
    }

    private function obterValorComposto(): string
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
                $valores[] = $this->obterValorComposto();
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
