# Instanciando o terminal

[◂ Script de terminal](02-script-de-terminal.md) | [Voltar ao índice](indice.md) | [Criando comandos ▸](04-criando-comandos.md)
-- | -- | --

## 1. Implementação

A interpretação dos argumentos digitados pelo usuário acontece através da instância da classe `Freep\Console\Terminal`, que pode ser configurada da seguinte maneira:

```php
$terminal = new Terminal(__DIR__ . "/src");
$terminal->setHowToUse("./example command [options] [arguments]");
$terminal->loadCommandsFrom(__DIR__ . "/tests/FakeApp/ContextOne/src/Commands");
$terminal->loadCommandsFrom(__DIR__ . "/tests/FakeApp/ContextTwo");

$terminal->run($argv);
```

## 2. Métodos disponíveis

### 2.1. O diretório de trabalho

```php
$terminal = new Terminal(__DIR__ . "/src");
```

A instância de `Freep\Console\Terminal` deve ser criada, especificando um **"diretório de trabalho"**. Este diretório, efetivamente, não tem causará nenhum efeito colateral.

É apenas uma forma de dizer, a todos os comandos os comandos existentes, qual é o *"diretório principal"* do projeto atual.

Geralmente, o **"diretório de trabalho"** será o diretório raiz da aplicação que usará a biblioteca para interpretar seus comandos. Dessa forma, os comandos poderão saber onde se encontra a estrutura do projeto.

### 2.2. O modo de usar

```php
$terminal->setHowToUse("./example command [options] [arguments]");
```

Especifica a mensagem de ajuda sobre o formato do comando. Note que leva em consideração
o nome do script atual, ou seja, `example`.

### 2.3. Diretório de comandos

```php
$terminal->loadCommandsFrom(__DIR__ . "/tests/FakeApp/ContextOne/src/Commands");
$terminal->loadCommandsFrom(__DIR__ . "/tests/FakeApp/ContextTwo");
```

Inúmeros diretórios contendo comandos poderão ser especificados. Cada um será varrido pela biblioteca a fim de identificar os comandos disponíveis.

Quando o usuario digitar `./example --help`, as informações de ajuda de todos os comandos será utilizada para exibir uma tela de ajuda abrangente no terminal do usuário.

### 2.4. Interpretar a entrada do usuário

```php
$terminal->run($argv);
```

Os argumentos digitados pelo usuário no terminal do sistema operacional são interpretados
aqui, usando a variável reservada do PHP chamada "$argv". Ela contem  lista de palavras
digitadas no terminal e está presente somente quando um script PHP for executado em CLI,
ou seja, no terminal.

Mais informações da documentação do PHP em [Reserved Variables](https://www.php.net/manual/pt_BR/reserved.variables.argv.php)

[◂ Script de terminal](02-script-de-terminal.md) | [Voltar ao índice](indice.md) | [Criando comandos ▸](04-criando-comandos.md)
-- | -- | --
