<?php

declare(strict_types=1);

namespace Freep\Console;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
class Message
{
    private string $icon = '';

    private string $prefix = '';

    private string $sufix = '';

    private string $lineEnd = '';

    private bool $quietMode = false;

    public function __construct(private string $message)
    {
    }

    public function enableQuietMode(): void
    {
        $this->quietMode = true;
    }

    private function lineBreak(): void
    {
        $this->lineEnd = "\n";
    }

    private function reset(): void
    {
        $this->icon   = "";
        $this->prefix = "";
        $this->sufix  = "";
        $this->lineEnd     = "";
    }

    private function wrapperBlue(): void
    {
        $this->prefix = "\033[0;34m";
        $this->sufix = "\033[0m";
    }

    private function wrapperGreen(): void
    {
        $this->prefix = "\033[0;32m";
        $this->sufix = "\033[0m";
    }

    private function wrapperRed(): void
    {
        $this->prefix = "\033[0;31m";
        $this->sufix = "\033[0m";
    }

    private function wrapperYellow(): void
    {
        $this->prefix = "\033[0;33m";
        $this->sufix = "\033[0m";
    }

    public function blue(): void
    {
        $this->wrapperBlue();
        $this->output();
    }

    public function blueLn(): void
    {
        $this->lineBreak();
        $this->blue();
    }

    public function green(): void
    {
        $this->wrapperGreen();
        $this->output();
    }

    public function greenLn(): void
    {
        $this->lineBreak();
        $this->green();
    }

    public function red(): void
    {
        $this->wrapperRed();
        $this->output();
    }

    public function redLn(): void
    {
        $this->lineBreak();
        $this->red();
    }

    public function yellow(): void
    {
        $this->wrapperYellow();
        $this->output();
    }

    public function yellowLn(): void
    {
        $this->lineBreak();
        $this->yellow();
    }

    public function error(): void
    {
        $this->icon = "✗ ";
        $this->red();
    }

    public function errorLn(): void
    {
        $this->lineBreak();
        $this->error();
    }

    public function info(): void
    {
        $this->icon = "➜ ";
        $this->blue();
    }

    public function infoLn(): void
    {
        $this->lineBreak();
        $this->info();
    }

    public function success(): void
    {
        $this->icon = "✔ ";
        $this->green();
    }

    public function successLn(): void
    {
        $this->lineBreak();
        $this->success();
    }

    public function warning(): void
    {
        $this->icon = "✱ ";
        $this->yellow();
    }

    public function warningLn(): void
    {
        $this->lineBreak();
        $this->warning();
    }

    public function output(): void
    {
        if ($this->quietMode === true) {
            return;
        }

        $resource = fopen('php://output', 'w');
        $message = $this->prefix . $this->icon . $this->message . $this->sufix . $this->lineEnd;
        fwrite($resource, $message); // @phpstan-ignore-line
        fclose($resource); // @phpstan-ignore-line

        $this->reset();
    }

    public function outputLn(): void
    {
        $this->lineBreak();
        $this->output();
    }
}
