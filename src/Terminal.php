<?php

declare(strict_types=1);

namespace Freep\Console;

use Freep\Console\Commands\Help;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class Terminal
{
    private string $appPath;

    /** @var array<string> */
    private array $directoryList = [];

    private string $executedCommand = "no";

    private string $howToUse = "";

    public function __construct(string $appPath)
    {
        $realPath = realpath($appPath);

        if ($realPath === false || is_dir($realPath) === false) {
            throw new InvalidArgumentException("O diretório de aplicação especificado não existe");
        }

        $this->appPath = $realPath;

        $this->loadCommandsFrom(__DIR__ . '/Commands');
    }

    public function setHowToUse(string $text): void
    {
        $this->howToUse = $text;
    }

    public function getHowToUse(): string
    {
        return $this->howToUse;
    }

    public function getAppPath(): string
    {
        return $this->appPath;
    }

    public function loadCommandsFrom(string $commandsPath): self
    {
        $realPath = realpath($commandsPath);

        if ($realPath === false || is_dir($realPath) === false) {
            throw new InvalidArgumentException("The directory specified for commands does not exist");
        }

        $this->directoryList[] = $commandsPath;
        return $this;
    }

    /**
     * @param array<int,string> $arguments
     */
    public function run(array $arguments): void
    {
        if (isset($arguments[0]) === false) {
            return;
        }

        if (in_array($arguments[0], ['--help', '-h']) === true) {
            $arguments[0] = "help";
        }

        $commandName = array_shift($arguments);

        ini_set("display_errors", "1");

        try {
            $this->runCommand($commandName, $arguments);
        } catch (Throwable $e) {
            echo "\033[0;31m✗  " . $e->getFile() . " on line " . $e->getLine() . "\033[0m\n";
            echo "\033[0;31m   " . $e->getMessage() . "\033[0m\n";
        }
    }

    /** @return array<int,string> */
    public function getCommandList(): array
    {
        $allCommands = [];

        foreach ($this->directoryList as $path) {
            $allCommands = array_merge($allCommands, $this->directoryList($path));
        }

        return $allCommands;
    }

    /**
     * @param array<int,string> $arguments
     */
    private function runCommand(string $name, array $arguments): void
    {
        $commandName = $this->normalizeCommandName($name);

        $allCommands = $this->getCommandList();

        if ($commandName === "Help") {
            (new Help($this))->run($arguments);
            $this->executedCommand = Help::class;
            return;
        }

        foreach ($allCommands as $commandFile) {
            $commandClassName = $this->parseClassName($commandFile);

            if (class_exists($commandClassName) === false) {
                continue;
            }

            /** @var Command $commandObject */
            $commandObject = (new $commandClassName($this));
            if ($commandName !== $this->normalizeCommandName($commandObject->getName())) {
                continue;
            }

            $commandObject->run($arguments);
            $this->executedCommand = $commandClassName;
            return;
        }

        echo "\033[0;31m✗ '{$name}' command not found\033[0m\n";
        (new Help($this))->run($arguments);
    }

    /** @return array<int,string> */
    private function directoryList(string $path): array
    {
        $contextPath = array_diff(
            (array)scandir($path),
            ['.', '..', '.gitkeep']
        );

        return array_map(fn($command) => "$path/$command", $contextPath);
    }

    private function extractNamespace(string $oneFile): string
    {
        if (is_file($oneFile) === false) {
            return "";
        }

        $allLines = (array)file($oneFile);
        foreach ($allLines as $line) {
            $line = (string)$line;
            if (str_starts_with(trim($line), "namespace") === true) {
                return trim(str_replace(["namespace ", ";"], "", $line));
            }
        }

        throw new RuntimeException("Unable to extract namespace from file '{$oneFile}'");
    }

    private function extractClassName(string $commandFile): string
    {
        return str_replace('.php', '', array_slice(explode("/", $commandFile), -1)[0]);
    }

    public function parseClassName(string $commandFile): string
    {
        return $this->extractNamespace($commandFile)
            . "\\" . $this->extractClassName($commandFile);
    }

    private function normalizeCommandName(string $kebabCaseName): string
    {
        // make:user-controller -> [Make, User-controller]
        $kebabCase = array_map(
            fn($noh) => ucfirst($noh),
            explode(":", $kebabCaseName)
        );

        $nameWithoutAColon = implode("", $kebabCase); // MakeUser-controller

        // MakeUser-controller -> [MakeUser, Controller]
        $pascalCase = array_map(
            fn($noh) => ucfirst($noh),
            explode("-", $nameWithoutAColon)
        );

        return implode("", $pascalCase); // MakeUserController
    }

    public function executedCommand(): string
    {
        return $this->executedCommand;
    }
}
