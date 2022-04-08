# Freep Console

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
## Controle de qualidade

Para o desenvolvimento, foram utilizadas ferramentas para testes de unidade e análise estática. Todas configuradas no nível máximo de exigência.

São as seguintes ferramentas:

- [PHP Unit](https://phpunit.de)
- [PHP Stan](https://phpstan.org)
- [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [PHP MD](https://phpmd.org)

