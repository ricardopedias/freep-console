<?php

declare(strict_types=1);

namespace Freep\Console;

use OutOfRangeException;

class Argumentos
{
    /**
     * @param array<string,string> $mapaNotacoes Mapa de notações curtas/longas com a notação principal
     * @param array<string,mixed> $comChave Valores identificáveis pela notação principal
     * @param array<string,string> $semChave Valores que não pertencem a nenhuma opção
     */
    public function __construct(
        private array $mapaNotacoes,
        private array $comChave,
        private array $semChave
    ) {}

    public function argumento(int $posicao): ?string
    {
        return $this->semChave[$posicao] ?? null;
    }

    /** @return mixed */
    public function opcao(string $notacao)
    {
        if (isset($this->mapaNotacoes[$notacao]) === false) {
            throw new OutOfRangeException("A opção '{$notacao}' é inválida");
        }

        $notacaoPrincipal = $this->mapaNotacoes[$notacao];
        return $this->comChave[$notacaoPrincipal] ?? null;
    }
}
