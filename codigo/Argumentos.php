<?php

declare(strict_types=1);

namespace Freep\Console;

use OutOfRangeException;

class Argumentos
{
    /**
     * @param array<string,string> $mapaDeNotacoes Mapa de notações curtas/longas com a notação principal
     * @param array<string,mixed> $comChave Valores identificáveis pela notação principal
     * @param array<int,string> $semChave Valores que não pertencem a nenhuma opção
     */
    public function __construct(
        private array $mapaDeNotacoes,
        private array $comChave,
        private array $semChave
    ) {
    }

    public function argumento(int $posicao): ?string
    {
        return $this->semChave[$posicao] ?? null;
    }

    /** @return array<int,string> */
    public function listaDeArgumentos(): array
    {
        return $this->semChave;
    }

    /** @return array<string,Opcao> */
    public function listaDeOpcoes(): array
    {
        return $this->comChave;
    }

    /** @return mixed */
    public function opcao(string $notacao)
    {
        if (isset($this->mapaDeNotacoes[$notacao]) === false) {
            throw new OutOfRangeException("A opção '{$notacao}' é inválida");
        }

        $notacaoPrincipal = $this->mapaDeNotacoes[$notacao];
        return $this->comChave[$notacaoPrincipal] ?? null;
    }
}
