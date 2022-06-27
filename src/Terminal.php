<?php

declare(strict_types=1);

namespace Freep\Console;

use Freep\Console\Commands\Help;
use Freep\Security\Filesystem;
use Freep\Security\Path;
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
        try {
            $realPath = (new Path($appPath))->getRealPath();
        } catch (InvalidArgumentException) {
            throw new InvalidArgumentException("The specified application directory does not exist");
        }

        $this->appPath = $realPath;

        $this->loadCommandsFrom(__DIR__ . '/Commands');
    }

    public function factoryMessage(string $message): Message
    {
        return new Message($message);
    }

    private function filesystem(string $contextPath): Filesystem
    {
        return new Filesystem($contextPath);
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
        try {
            $realPath = (new Path($commandsPath))->getRealPath();
        } catch (InvalidArgumentException) {
            throw new InvalidArgumentException("The directory specified for commands does not exist");
        }

        $this->directoryList[] = $realPath;
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

        try {
            $this->runCommand($commandName, $arguments);
        } catch (Throwable $e) {
            $this->factoryMessage($e->getFile() . " on line " . $e->getLine())->error();
            $this->factoryMessage("   " . $e->getMessage())->red();
        }
    }

    /** @return array<int,string> */
    public function getCommandList(): array
    {
        $allCommands = [];

        foreach ($this->directoryList as $path) {
            $allCommands = array_merge(
                $allCommands,
                $this->filesystem($path)->getDirectoryFiles('/')
            );
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
                throw new RuntimeException(
                    "The file '$commandFile' not contains a '$commandClassName' class"
                );
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

        $this->factoryMessage("'{$name}' command not found")->error();
        (new Help($this))->run($arguments);
    }

    private function extractNamespace(string $oneFile): string
    {
        $pathInfo = new Path($oneFile);
        $contextPath = $pathInfo->getDirectory();
        $file = $pathInfo->getFile();

        $allLines = $this->filesystem($contextPath)->getFileRows($file);
        foreach ($allLines as $line) {
            $line = (string)$line;

            if (str_starts_with(trim($line), "namespace") === true) {
                $limit = strpos($line, ';');
                $limit = $limit === false ? null : $limit;

                $line = substr($line, 0, $limit);

                return trim(str_replace("namespace ", "", $line));
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
