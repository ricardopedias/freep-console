<?php

declare(strict_types=1);

namespace Tests;

use Closure;
use Freep\Console\Terminal;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

class TestCase extends FrameworkTestCase
{
    protected function gotcha(object $terminal, Closure $callback): string
    {
        ob_start();
        $callback($terminal);
        return (string)ob_get_clean();
    }

    protected function terminalFactory(): Terminal
    {
        $terminal = new Terminal(__DIR__ . "/FakeApp");
        $terminal->setHowToUse("./example command [options] [arguments]");
        $terminal->loadCommandsFrom(__DIR__ . "/FakeApp/ContextOne/src/Commands");
        $terminal->loadCommandsFrom(__DIR__ . "/FakeApp/ContextTwo");
        return $terminal;
    }
}
