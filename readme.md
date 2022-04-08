# Freep Console

![PHP Version](https://img.shields.io/badge/php-%5E8.0-blue)
![License](https://img.shields.io/badge/license-MIT-blue)
![TDD](https://img.shields.io/badge/tdd-Tested%20100%25-blue)
[![Follow](https://img.shields.io/github/followers/ricardopedias?label=Siga%20no%20GitHUB&style=social)](https://github.com/ricardopedias)
[![Twitter](https://img.shields.io/twitter/follow/ricardopedias?label=Siga%20no%20Twitter)](https://twitter.com/ricardopedias)


## Sinopse

Este repositório contém as funcionalidades necessárias para implementar um gerenciador de 
comandos para terminal em uma aplicação PHP de forma fácil.

Para informações detalhadas, consulte a [documentação](docs/indice.md);

## Modo de Usar

### 1. Crie um comando

Implemente um comando chamado "meu-comando", baseado na classe abstrata `Freep\Console\Comando`:

```php
class MeuComando extends Comando
{
    protected function inicializar(): void
    {
        $this->setarNome("meu-comando");
        $this->adicionarOpcao(
            new Opcao('-l', '--ler', 'Lê um arquivo texto', Opcao::OBRIGATORIA)
        );
    }

    protected function manipular(Argumentos $argumentos): void
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

$terminal = new Freep\Console\Terminal("/diretorio/de/comandos");
$terminal->executar($argv);
```

### 3. Execute o script

```bash
$ ./meuconsole meu-comando -l
# exibe: Olá
```

```bash
$ ./meuconsole meu-comando --ajuda
# exibe:
#
# Comando: meu-comando
# Executa o comando meu-comando
#
# Modo de usar:
# ./meuconsole meu-comando [opcoes]
#
# Opções:
# -a, --ajuda     Exibe a ajuda do comando
# -l, --ler       Lê um arquivo texto
```

```bash
$ ./meuconsole --ajuda
# exibe:
#
# Modo de usar:
# ./meuconsole comando [opcoes] [argumentos]
#
# Opções:
# -a, --ajuda     Exibe as informações de ajuda
#
# Comandos disponíveis:
# ajuda           Exibe as informações de ajuda
# meu-comando     Executa o comando meu-comando
```

## Características

-   Feito para o PHP 8.0 ou superior;
-   Codificado com boas práticas e máxima qualidade;
-   Bem documentado e amigável para IDEs;
-   Feito com TDD (Test Driven Development);
-   Implementado com testes de unidade usando PHPUnit;
-   Feito com :heart: &amp; :coffee:.

## Sumário

- [Modo de Usar](docs/01-modo-de-usar.md)
- [Script de terminal](docs/02-script-de-terminal.md)
- [Instanciando o Terminal](docs/03-instanciando-o-terminal.md)
- [Criando Comandos](docs/04-criando-comandos.md)
- [Implementando Opções](docs/05-implementando-opcoes.md)
- [Usando os argumentos](docs/06-usando-os-argumentos.md)
- [Evoluindo a biblioteca](docs/07-evoluindo-a-biblioteca.md)

## Creditos

[Ricardo Pereira Dias](https://www.ricardopedias.com.br)
