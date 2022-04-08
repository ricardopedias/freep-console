# Instanciando o terminal

- [Voltar ao índice](indice.md)
- [Script de terminal](02-script-de-terminal.md)

## Implementação

A interpretação dos argumentos digitados pelo usuário acontece através da instância 
da classe `Freep\Console\Terminal`, que pode ser configurada da seguinte maneira:

```php
$terminal = new Terminal(__DIR__ . "/codigo");
$terminal->setarModoDeUsar("./superapp comando [opcoes] [argumentos]");
$terminal->carregarComandosDe(__DIR__ . "/testes/AppFalso/ContextoUm/src/Comandos");
$terminal->carregarComandosDe(__DIR__ . "/testes/AppFalso/ContextoDois");

$terminal->executar($argv);
```

## Métodos disponíveis

### O diretório de trabalho

```php
$terminal = new Terminal(__DIR__ . "/codigo");
```

A instância de `Freep\Console\Terminal` deve ser criada, especificando um **"diretório 
de trabalho"**. Este diretório, efetivamente, não tem causará nenhum efeito colateral. 

É apenas uma forma de dizer, a todos os comandos os comandos existentes, qual é o 
*"diretório principal"* do projeto atual. 

Geralmente, o **"diretório de trabalho"** será o diretório raiz da aplicação que usará 
a biblioteca para interpretar seus comandos. Dessa forma, os comandos poderão saber
onde se encontra a estrutura do projeto.

### O modo de usar

```php
$terminal->setarModoDeUsar("./superapp comando [opcoes] [argumentos]");
```

Especifica a mensagem de ajuda sobre o formato do comando. Note que leva em consideração
o nome do script atual, ou seja, `superapp`.


### Diretório de comandos

```php
$terminal->carregarComandosDe(__DIR__ . "/testes/AppFalso/ContextoUm/src/Comandos");
$terminal->carregarComandosDe(__DIR__ . "/testes/AppFalso/ContextoDois");
```

Inúmeros diretórios contendo comandos poderão ser especificados. Cada um será
varrido pela biblioteca a fim de identificar os comandos disponíveis.

Quando o usuario digitar `./superapp --ajuda`, as informações de ajuda de todos os 
coamndos será utilizada para exibir uma tela de ajuda abrangente no terminal do usuário.

### Interpretar a entrada do usuário

```php
$terminal->executar($argv);
```

Os argumentos digitados pelo usuário no terminal do sistema operacional são interpretados
aqui, usando a variável reservada do PHP chamada "$argv". Ela contem  lista de palavras
digitadas no terminal e está presente somente quando um script PHP for executado em CLI,
ou seja, no terminal.

- [Criando Comandos](04-criando-comandos.md)
- [Voltar ao índice](indice.md)