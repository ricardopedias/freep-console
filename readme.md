# Freep Console

![PHP Version](https://img.shields.io/badge/php-%5E8.0-blue)
![License](https://img.shields.io/badge/license-MIT-blue)
![TDD](https://img.shields.io/badge/tdd-Tested%20100%25-blue)
[![Follow](https://img.shields.io/github/followers/ricardopedias?label=Siga%20no%20GitHUB&style=social)](https://github.com/ricardopedias)
[![Twitter](https://img.shields.io/twitter/follow/ricardopedias?label=Siga%20no%20Twitter)](https://twitter.com/ricardopedias)


## Synopsis

This repository contains the necessary functionality to easily implement a terminal command manager in a PHP application.

For detailed information, consult the documentation in [English](docs/en/index.md) or [Portuguese](docs/pt-br/indice.md). See also this readme in [Portuguese](docs/pt-br/leiame.md).

## How to use

### 1. Create a command

Implement a command called "my-command", based on the abstract class `Freep\Console\Command`:

```php
class MyCommand extends Command
{
    protected function initialize(): void
    {
        $this->setName("my-command");
        $this->addOption(
            new Option('-r', '--read', 'Read a text file', Option::REQUIRED)
        );
    }

    protected function handle(Arguments $arguments): void
    {
        $this->info("Hello");
    }
}
```

### 2. Create a script

Create a file, call it for example "myconsole", and add the following content:

```php
#!/bin/php
<?php
include __DIR__ . "/vendor/autoload.php";

array_shift($argv);

$terminal = new Freep\Console\Terminal("/directory/containing/commands");
$terminal->run($argv);
```

### 3. Run the script

```bash
$ ./myconsole my-command -r
# will display: Hello
```

```bash
$ ./myconsole my-command --help
# will display:
#
# Comando: my-command
# Executa o comando meu-comando
#
# Modo de usar:
# ./myconsole my-command [opcoes]
#
# Opções:
# -h, --help     Exibe a ajuda do comando
# -r, --read     Lê um arquivo texto
```

```bash
$ ./myconsole --help
# will display:
#
# Modo de usar:
# ./myconsole command [opcoes] [argumentos]
#
# Opções:
# -h, --help     Exibe as informações de ajuda
#
# Comandos disponíveis:
# help           Exibe as informações de ajuda
# my-command     Executa o comando meu-comando
```

## Characteristics

- Made for PHP 8.0 or higher;
- Codified with best practices and maximum quality;
- Well documented and IDE friendly;
- Made with TDD (Test Driven Development);
- Implemented with unit tests using PHPUnit;
- Made with :heart: &amp; :coffee:.

## Summary

- [How to use](docs/en/01-modo-de-usar.md)
- [Terminal script](docs/en/02-script-de-terminal.md)
- [Instantiating the Terminal](docs/en/03-instanciando-o-terminal.md)
- [Criando Comandos](docs/en/04-criando-comandos.md)
- [Creating Commands](docs/en/05-implementando-opcoes.md)
- [Using the arguments](docs/en/06-usando-os-argumentos.md)
- [Improving the library](docs/en/07-evoluindo-a-biblioteca.md)

## Creditos

[Ricardo Pereira Dias](https://www.ricardopedias.com.br)
