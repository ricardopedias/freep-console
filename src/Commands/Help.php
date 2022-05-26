<?php

declare(strict_types=1);

namespace Freep\Console\Commands;

use Freep\Console\Arguments;
use Freep\Console\Command;

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

            if (class_exists($commandClassName) === false) {
                continue;
            }

            $commandObject = (new $commandClassName($this->getTerminal()));
            $list[] = $commandObject;
        }

        return $list;
    }

    private function printCommand(string $comando, string $descricao): void
    {
        $coluna = 20;
        $caracteres = mb_strlen($comando);
        $espacamento = $caracteres < $coluna
            ? str_repeat(" ", $coluna - $caracteres)
            : " ";

        $involucro = "\033[0;32m%s \033[0m %s";
        $this->line(sprintf($involucro, $comando, $espacamento . $descricao));
    }
}
