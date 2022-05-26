<?php

declare(strict_types=1);

namespace Freep\Console;

use InvalidArgumentException;

abstract class Command
{
    private string $name = 'sem-nome';

    private string $description = "Executar o comando 'sem-nome'";

    private string $howToUse = "";

    private Terminal $terminal;

    /** @var array<int,Option> */
    private array $options = [];

    public function __construct(Terminal $terminal)
    {
        $this->terminal = $terminal;

        $this->addOption(
            new Option('-h', '--help', "Exibe a ajuda do comando", Option::OPTIONAL)
        );

        $this->initialize();
    }

    protected function setName(string $commandName): void
    {
        if (strpos($commandName, " ") !== false) {
            throw new InvalidArgumentException(
                "O nome de um comando deve ser no formato kebab-case. Ex: nome-do-comando"
            );
        }

        $this->name = $commandName;
        $this->description = "Executar o comando '{$commandName}'";
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
        $this->printSection("Comando: " . $this->getName());

        $this->line(" " . $this->getDescription());

        if ($this->getHowToUse() !== "") {
            $this->printSection("Modo de usar:");
            $this->line($this->getHowToUse());
        }

        $this->printSection("Opções:");

        foreach ($this->getOptions() as $option) {
            $this->printOption($option->getShortNotation(), $option->getLongNotation(), $option->getDescription());
        }
    }

    protected function printSection(string $text): void
    {
        $this->line("\n\033[0;33m{$text} \033[0m");
    }

    protected function printOption(string $shortNotation, string $longNotation, string $description): void
    {
        $argument = "{$shortNotation}, {$longNotation}";

        $column = 20;
        $characters = mb_strlen($argument);
        $spacing = $characters < $column
            ? str_repeat(" ", $column - $characters)
            : " ";

        $involucro = " \033[0;32m%s \033[0m %s";
        $this->line(sprintf($involucro, $argument, $spacing . $description));
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
        $this->print($text . "\n");
    }

    protected function error(string $text): void
    {
        $this->line("\033[0;31m✗  {$text}\033[0m");
    }

    protected function info(string $text): void
    {
        $this->line("\033[0;32m➜  {$text}\033[0m");
    }

    protected function warning(string $text): void
    {
        $this->line("\033[0;33m{$text}\033[0m");
    }

    private function print(string $text): void
    {
        $resource = fopen('php://output', 'w');
        fwrite($resource, $text); // @phpstan-ignore-line
        fclose($resource); // @phpstan-ignore-line
    }
}
