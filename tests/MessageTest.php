<?php

declare(strict_types=1);

namespace Tests;

use Closure;
use Freep\Console\Message;
use PHPUnit\Framework\TestCase;

/** @SuppressWarnings(PHPMD.TooManyPublicMethods) */
class MessageTest extends TestCase
{
    private function gotcha(string $message, Closure $callback): string
    {
        $object = new Message($message);

        ob_start();
        $callback($object);
        return (string)ob_get_clean();
    }

    /** @test */
    public function blue(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->blue()
        );

        $this->assertSame("\033[0;34mSimple message\033[0m", $result);
    }

    /** @test */
    public function blueLn(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->blueLn()
        );

        $this->assertSame("\033[0;34mSimple message\033[0m\n", $result);
    }

    /** @test */
    public function green(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->green()
        );

        $this->assertSame("\033[0;32mSimple message\033[0m", $result);
    }

    /** @test */
    public function greenLn(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->greenLn()
        );

        $this->assertSame("\033[0;32mSimple message\033[0m\n", $result);
    }

    /** @test */
    public function red(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->red()
        );

        $this->assertSame("\033[0;31mSimple message\033[0m", $result);
    }

    /** @test */
    public function redLn(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->redLn()
        );

        $this->assertSame("\033[0;31mSimple message\033[0m\n", $result);
    }

    /** @test */
    public function yellow(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->yellow()
        );

        $this->assertSame("\033[0;33mSimple message\033[0m", $result);
    }

    /** @test */
    public function yellowLn(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->yellowLn()
        );

        $this->assertSame("\033[0;33mSimple message\033[0m\n", $result);
    }

    /** @test */
    public function error(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->error()
        );

        $this->assertSame("\033[0;31m✗ Simple message\033[0m", $result);
    }

    /** @test */
    public function errorLn(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->errorLn()
        );

        $this->assertSame("\033[0;31m✗ Simple message\033[0m\n", $result);
    }

    /** @test */
    public function info(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->info()
        );

        $this->assertSame("\033[0;34m➜ Simple message\033[0m", $result);
    }

    /** @test */
    public function infoLn(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->infoLn()
        );

        $this->assertSame("\033[0;34m➜ Simple message\033[0m\n", $result);
    }

    /** @test */
    public function success(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->success()
        );

        $this->assertSame("\033[0;32m✔ Simple message\033[0m", $result);
    }

    /** @test */
    public function successLn(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->successLn()
        );

        $this->assertSame("\033[0;32m✔ Simple message\033[0m\n", $result);
    }

    /** @test */
    public function warning(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->warning()
        );

        $this->assertSame("\033[0;33m✱ Simple message\033[0m", $result);
    }

    /** @test */
    public function warningLn(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->warningLn()
        );

        $this->assertSame("\033[0;33m✱ Simple message\033[0m\n", $result);
    }

    /** @test */
    public function output(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->output()
        );

        $this->assertSame("Simple message", $result);
    }

    /** @test */
    public function outputLn(): void
    {
        $result = $this->gotcha(
            'Simple message',
            fn(Message $object) => $object->outputLn()
        );
        $this->assertSame("Simple message\n", $result);
    }
}
