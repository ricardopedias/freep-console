<?php

declare(strict_types=1);

namespace Freep\Console;

use InvalidArgumentException;

class Opcao
{
    public const OPCIONAL = 4;

    public const OBRIGATORIA = 8;

    public const COM_VALOR = 16;

    private string $notacaoCurta;

    private string $notacaoLonga;

    private string $descricao = '';

    private string $valorPadrao = "";

    private bool $obrigatoria = false;

    private bool $valorada = false;

    private bool $booleana = false;

    public function __construct(
        ?string $notacaoCurta,
        ?string $notacaoLonga,
        string $descricao,
        int $tipo,
        ?string $valorPadrao = null
    ) {
        if ($notacaoCurta === null && $notacaoLonga === null) {
            throw new InvalidArgumentException("É obrigatório fornecer pelo menos uma notação");
        }

        $this->validarNotacaoCurta($notacaoCurta);
        $this->validarNotacaoLonga($notacaoLonga);

        $this->notacaoCurta = $notacaoCurta ?? $notacaoLonga;
        $this->notacaoLonga = $notacaoLonga ?? $notacaoCurta;
        $this->descricao    = $descricao;
        $this->valorPadrao  = $valorPadrao ?? "";

        $tiposObrigatorios = [
            Opcao::OBRIGATORIA,
            Opcao::OBRIGATORIA | Opcao::COM_VALOR
        ];
        if (in_array($tipo, $tiposObrigatorios) === true) {
            $this->obrigatoria = true;
        }

        $tiposValorados = [
            Opcao::OPCIONAL | Opcao::COM_VALOR,
            Opcao::OBRIGATORIA | Opcao::COM_VALOR,
        ];
        if (in_array($tipo, $tiposValorados) === true) {
            $this->valorada = true;
        }

        $tiposBooleanos = [
            Opcao::OPCIONAL,
            Opcao::OBRIGATORIA
        ];

        if (in_array($tipo, $tiposBooleanos) === true) {
            $this->booleana = true;
        }

        if ($this->booleana === true && in_array($this->valorPadrao, ["0", "1", ""]) === false) {
            throw new InvalidArgumentException("Um valor booleano deve ser '0' ou '1'");
        }
    }

    private function validarNotacaoCurta(?string $notacaoCurta): void
    {
        if (
            $notacaoCurta !== null
            && (str_starts_with($notacaoCurta, "--") === true || str_starts_with($notacaoCurta, "-") === false)
        ) {
            throw new InvalidArgumentException("A notação curta deve iniciar com um traço");
        }
    }

    private function validarNotacaoLonga(?string $notacaoLonga): void
    {
        if ($notacaoLonga !== null && str_starts_with($notacaoLonga, "--") === false) {
            throw new InvalidArgumentException("A notação longa deve iniciar com dois traços");
        }
    }

    /**
     * Devolve a notação principal da opção (short ou long)
     * Se ambas notações estiverem presentes no construtor, a mais curta será a principal.
     * Caso a mais curta for setada como nula no construtor, a longa será a principal
     */
    public function notacaoPrincipal(): string
    {
        return $this->notacaoCurta ?? $this->notacaoLonga;
    }

    public function notacaoCurta(): string
    {
        return $this->notacaoCurta;
    }

    public function notacaoLonga(): string
    {
        return $this->notacaoLonga;
    }

    public function descricao(): string
    {
        return $this->descricao;
    }

    public function valorPadrao(): string
    {
        return $this->valorPadrao;
    }

    public function obrigatoria(): bool
    {
        return $this->obrigatoria;
    }

    public function booleana(): bool
    {
        return $this->booleana;
    }

    public function valorada(): bool
    {
        return $this->valorada;
    }
}
