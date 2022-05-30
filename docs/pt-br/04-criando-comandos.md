# Criando Comandos

[◂ Instanciando o terminal](03-instanciando-o-terminal.md) | [Voltar ao índice](indice.md) | [Implementando opções ▸](05-implementando-opcoes.md)
-- | -- | --

## 1. Sobre um comando

Todos os comandos devem ser implementados com base na classe abstrata `Freep\Console\Command`:

```php
abstract class Command
{
    abstract protected function initialize(): void;

    abstract protected function handle(Arguments $arguments): void;
}
```

## 2. Método inicializar

### 2.1. Sobre

No método `"Command->inicializar()"` devem ser implementadas as configurações do comando, como o nome, a mensagem de ajuda, as opções, etc.

Uma implementação mínima deve conter ao menos o método `"Command->setarNome()"`, que fornece o nome do comando.

```php
class MeuComando extends Command
{
    protected function initialize(): void
    {
        $this->setName("meu-comando");

        // outras configurações do comando
    }

    //...
}
```

### 2.2. Setar o nome do comando

Especifica o nome do comando, ou seja, a palavra que o usuário digitará no terminal para invocá-lo.

```php
$this->setName("meu-comando");
```

### 2.3. Setar a descrição do comando

Especifica uma descrição sobre o objetivo do comando.
Esta mensagem será exibida nas informações de ajuda

```php
$this->setDescription("Exibe a mensagem 'olá' no terminal");
```

### 2.4. Setar o modo de usar

Especifica uma dica sobre como este comando pode ser utilizado.
Esta mensagem será exibida nas informações de ajuda.

```php
$this->setHowToUse("./example dizer-ola [opcoes]");
```

### 2.5. Adicionar uma opção

Adiciona uma opção ao comando, podendo ser *obrigatória*, *opcional* ou *valorada*.

Mais informações sobre opções em [Implementando Opções](05-implementando-opcoes.md).

```php
$this->addOption(new Option(
    '-d',
    '--destruir',
    'Apaga o arquivo texto após usá-lo',
    Option::OPTIONAL
));
```

## 3. Manipular argumentos

### 3.1. Sobre

Da mesma forma, o método `"Command->handle()"` deve ser implementado em todos os comandos. É neste método que a rotina do comando deverá ser implementada.

Neste método, é possível interagir com o usuário e obter informações sobre o que
ele forneceu como argumentos ao invocar o comando.

```php
class MeuComando extends Command
{
    // ...

    protected function handle(Arguments $arguments): void
    {
        // implementação da rotina do comando

        $this->info('Comando executado com sucesso');
    }

    //...
}
```

### 3.2. Obter o terminal atual

Obtém a intância do terminal atual, permitindo acessar informações úteis.

```php
$instancia = $this->getTerminal();
```

### 3.3. Obter o caminho da aplicação atual

Obtém o caminho completo até a raiz da aplicação. Pode-se especificar um sufixo,
para compor facilmente um caminho mais completo:

```php
echo $this->getAppPath();
// /home/ricardo/projeto

echo $this->getAppPath('console/php');
// /home/ricardo/projeto/console/php
```

### 3.4. Emitir uma mensagem

As mensagens são disparadas diretamente por métodos já existentes na classe abstrata `Freep\Console\Command`.
Por baixo dos panos, a classe `Freep\Console\Message` é usada para esse trabalho.
Mais informações sobre sua utilidade pode ser consultada em [Biblioteca de mensagens](08-biblioteca-de-mensagens.md).

### 3.4.1. Emitir um alerta

Exibe um texto detacado em laranja no terminal do usuário.

```php
echo $this->warning("Operação inexistente");
```

### 3.4.2. Emitir um erro

Exibe um texto detacado em vermelho no terminal do usuário.

```php
echo $this->error("Ocorreu um erro");
```

### 3.4.3. Emitir uma informação

Exibe um texto detacado em verde no terminal do usuário.

```php
echo $this->info("Operação executada");
```

### 3.4.4. Emitir um texto simples

Exibe um texto sem destaque no terminal do usuário.

```php
echo $this->line("Executando comando");
```

## 4. Objeto Argumentos

Para identificar as opções fornecidas pelo usuário no terminal, usa-se o objeto
`Freep\Console\Arguments`, que fornece acesso às opções, valores e argumentos
especificados.

Este objeto é disponibilizado como argumento do método `"Command->handle()"`.

Mais informações sobre argumentos em [Usando os Argumentos](06-usando-os-argumentos.md).

[◂ Instanciando o terminal](03-instanciando-o-terminal.md) | [Voltar ao índice](indice.md) | [Implementando opções ▸](05-implementando-opcoes.md)
-- | -- | --
