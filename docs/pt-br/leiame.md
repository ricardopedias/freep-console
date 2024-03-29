# Freep Console

[English](../../readme.md) | [Português](leiame.md)
-- | --

## Sinopse

Este repositório contém as funcionalidades necessárias para implementar um gerenciador de comandos para terminal em uma aplicação PHP de forma fácil.

```bash
composer require ricardopedias/freep-console
```

Para informações detalhadas, consulte o [Sumário da Documentação](indice.md).

## Modo de Usar

### 1. Crie um comando

Implemente um comando chamado "meu-comando", baseado na classe abstrata `Freep\Console\Command`:

```php
class MeuComando extends Command
{
protected function initialize(): void
{
$this->setName("meu-comando");
$this->addOption(
new Option('-l', '--ler', 'Lê um arquivo texto', Option::REQUIRED)
);
}

protected function handle(Arguments $arguments): void
{
$this->info("Olá");
}
}
```

### 2. Crie um script

Crie um arquivo, chame-o por exemplo de "meuconsole", e adicione o seguinte conteúdo:

```php
#!/bin/php
<?php
include __DIR__ . "/vendor/autoload.php";

array_shift($argv);

$terminal = new Freep\Console\Terminal("/diretorio/da/super/aplicacao");
$terminal->loadCommandsFrom("/diretorio/de/comandos");
$terminal->run($argv);
```

### 3. Execute o script

```bash
./meuconsole meu-comando -l
# exibe: Olá
```

```bash
./meuconsole meu-comando --help
# exibe:
#
# Command: meu-comando
# Run the 'meu-comando' command
#
# How to use:
# ./meuconsole meu-comando [options]
#
# Options:
# -h, --help   Display command help
# -r, --read   Lê um arquivo texto
```

```bash
$ ./meuconsole --ajuda
# exibe:
#
# How to use:
# ./meuconsole command [options] [arguments]
#
# Options:
# -h, --help   Display command help
#
# Available commands:
# help           Display command help
# meu-comando    Run the 'meu-comando' command
```

## Características

- Feito para o PHP 8.0 ou superior;
- Codificado com boas práticas e máxima qualidade;
- Bem documentado e amigável para IDEs;
- Feito com TDD (Test Driven Development);
- Implementado com testes de unidade usando PHPUnit;
- Feito com :heart: &amp; :coffee:.

## Créditos

[Ricardo Pereira Dias](https://www.ricardopedias.com.br)
