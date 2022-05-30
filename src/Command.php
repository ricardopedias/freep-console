<?php

declare(strict_types=1);

namespace Freep\Console;

use InvalidArgumentException;

abstract class Command
{
    private string $name = 'no-name';

    private string $description = "Run the 'no-name' command";

    private string $howToUse = "";

    private Terminal $terminal;

    /** @var array<int,Option> */
    private array $options = [];

    public function __construct(Terminal $terminal)
    {
        $this->terminal = $terminal;

        $this->addOption(
            new Option('-h', '--help', "Display command help", Option::OPTIONAL)
        );

        $this->initialize();
    }

    protected function setName(string $commandName): void
    {
        if (strpos($commandName, " ") !== false) {
            throw new InvalidArgumentException(
                "The name of a command must be in kebab-case format. Example: command-name"
            );
        }

        $this->name = $commandName;
        $this->description = "Run the '{$commandName}' command";
    }

    protected function setDescription(string $text): void
    {
        $this->description = $text;
    }

    protected function setHowToUse(string $text): void
    {
        $this->howToUse = $text;
    }

    protected function addOption(Option $option): void
    {
        $this->options[] = $option;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getHowToUse(): string
    {
        return $this->howToUse;
    }

    /** @return array<int,Option> */
    public function getOptions(): array
    {
        return $this->options;
    }

    abstract protected function initialize(): void;

    abstract protected function handle(Arguments $arguments): void;

    /** @param array<int,string> $commandArguments */
    public function run(array $commandArguments): void
    {
        $parser = new Parser($this->getOptions());
        $arguments = $parser->parseArgumentList($commandArguments);

        if ($arguments->getOption('-h') === '1') {
            $this->printHelp();
            return;
        }

        $this->handle($arguments);
    }

    private function printHelp(): void
    {
        $this->printSection("Command: " . $this->getName());

        $this->line(" " . $this->getDescription());

        if ($this->getHowToUse() !== "") {
            $this->printSection("How to use:");
            $this->line($this->getHowToUse());
        }

        $this->printSection("Options:");

        foreach ($this->getOptions() as $option) {
            $this->printOption($option->getShortNotation(), $option->getLongNotation(), $option->getDescription());
        }
    }

    protected function printSection(string $text): void
    {
        $this->getTerminal()->factoryMessage("\n$text ")->yellowLn();
    }

    protected function printOption(string $shortNotation, string $longNotation, string $description): void
    {
        $argument = "{$shortNotation}, {$longNotation}";

        $column = 20;
        $characters = mb_strlen($argument);
        $spacing = $characters < $column
            ? str_repeat(" ", $column - $characters)
            : " ";


        $this->getTerminal()->factoryMessage("$argument ")->yellow();
        $this->getTerminal()->factoryMessage(" " . $spacing . $description)->outputLn();
    }

    protected function getTerminal(): Terminal
    {
        return $this->terminal;
    }

    protected function getAppPath(string $sufix = ""): string
    {
        return $this->getTerminal()->getAppPath() . "/" . trim($sufix, "/");
    }

    protected function line(string $text): void
    {
        $this->getTerminal()->factoryMessage($text)->outputLn();
    }

    protected function error(string $text): void
    {
        $this->getTerminal()->factoryMessage($text)->errorLn();
    }

    protected function info(string $text): void
    {
        $this->getTerminal()->factoryMessage($text)->infoLn();
    }

    protected function warning(string $text): void
    {
        $this->getTerminal()->factoryMessage($text)->warningLn();
    }
}
