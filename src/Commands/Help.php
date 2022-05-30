<?php

declare(strict_types=1);

namespace Freep\Console\Commands;

use Freep\Console\Arguments;
use Freep\Console\Command;
use RuntimeException;

class Help extends Command
{
    protected function initialize(): void
    {
        $this->setName("help");
        $this->setDescription("Display help information");
    }

    protected function handle(Arguments $arguments): void
    {
        if ($this->getTerminal()->getHowToUse() !== "") {
            $this->printSection("How to use:");
            $this->line("  " . $this->getTerminal()->getHowToUse());
        }

        $this->printSection("Options:");

        $this->printOption("-h", "--help", $this->getDescription());

        $this->printSection("Available commands:");

        $commandList = $this->getCommandList();

        foreach ($commandList as $command) {
            $this->printCommand($command->getName(), $command->getDescription());
        }
    }

    /** @return array<int,Command> */
    private function getCommandList(): array
    {
        $list = [];

        $commandList = $this->getTerminal()->getCommandList();

        foreach ($commandList as $commandFile) {
            $commandClassName = $this->getTerminal()->parseClassName($commandFile);

            $commandObject = (new $commandClassName($this->getTerminal()));
            $list[] = $commandObject;
        }

        return $list;
    }

    private function printCommand(string $command, string $description): void
    {
        $column = 20;
        $characters = mb_strlen($command);
        $spacing = $characters < $column
            ? str_repeat(" ", $column - $characters)
            : " ";

        $this->getTerminal()->factoryMessage("$command ")->yellow();
        $this->getTerminal()->factoryMessage(" " . $spacing . $description)->outputLn();
    }
}
