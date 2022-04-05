# Criando Comandos

- [Voltar ao índice](indice.md)
- [Instanciando o Terminal](03-instanciando-o-terminal.md)

## Sobre um comando

Todos os coamndos devem ser implementados com base na classe abstrata `Freep\Console\Comando`:

```php
abstract class Comando
{
    abstract protected function inicializar(): void;

    abstract protected function manipular(Argumentos $argumentos): void;
}
```

## Método inicializar

No método `"self->inicializar()"` devem ser implementadas as configurações do comando, como o nome, a mensagem de ajuda, as opções, etc.

Uma implementação mínima deve conter ao menos o método `"self->setarNome()"`, que fornece o nome do comando.

```php
class MeuComando extends Comando
{
    protected function inicializar(): void
    {
        $this->setarNome("meu-comando");

        // outras configurações do comando
    }

    //...
}
```

### Método Comando->setarNome

Especifica o nome do comando, ou seja, a palavra que o usuário digitará no terminal para invocá-lo.

```php
$this->setarNome("meu-comando");
```

### Método Comando->setarDescricao

Especifica uma descrição sobre o objetivo do comando.
Esta mensagem será exibida nas informações de ajuda
        

```php
$this->setarDescricao("Exibe a mensagem 'olá' no terminal");
```

### Método Comando->setarModoDeUsar

Especifica uma dica sobre como este comando pode ser utilizado.
Esta mensagem será exibida nas informações de ajuda        

```php
$this->setarModoDeUsar("./superapp dizer-ola [opcoes]");
```


### Método Comando->adicionarOpcao

Adiciona uma opção ao comando, podendo ser *obrigatória*, *opcional* ou *valorada*.

Mais informações sobre opções em [implementando opções](05-implementando-opcoes.md).

```php
$this->adicionarOpcao(new Opcao(
    '-d',
    '--destruir',
    'Apaga o arquivo texto após usá-lo',
    Opcao::OPCIONAL
));
```


## Método manipular

Da mesma forma, o método `"self->manipular()"` deve ser implementado em todos os comandos. É neste método que a rotina do comando deverá ser implementada.

Neste método, é possível interagir com o usuário e obter informações sobre o que
ele forneceu como argumentos ao invocar o comando.


```php
class MeuComando extends Comando
{
    // ...

    protected function manipular(Argumentos $argumentos): void
    {
        // implementação da rotina do comando

        $this->info('Comando executado com sucesso');
    }

    //...
}
```

### Método Comando->terminal()

Obtém a intância do terminal atual, permitindo acessar informações úteis.

```php
$instancia = $this->terminal();
```

### Método Comando->caminhoDaAplicacao()

Obtém o caminho completo até a raiz da aplicação. Pode-se especificar um sufixo,
para compor facilmente um caminho mais completo:

```php
echo $this->caminhoDaAplicacao();
// /home/ricardo/projeto

echo $this->caminhoDaAplicacao('console/php');
// /home/ricardo/projeto/console/php
```


### Método Comando->alerta()

Exibe um texto detacado em laranja no terminal do usuário.

```php
echo $this->alerta("Operação inexistente");
```

### Método Comando->erro()

Exibe um texto detacado em vermelho no terminal do usuário.

```php
echo $this->erro("Ocorreu um erro");
```

### Método Comando->info()

Exibe um texto detacado em verde no terminal do usuário.

```php
echo $this->info("Operação executada");
```

### Método Comando->linha()

Exibe um texto sem destaque no terminal do usuário.

```php
echo $this->linha("Executando comando");
```

## Método manipular: argumentos

Para identificar as opções fornecidas pelo usuário no terminal, usa-se o objeto
`Freep\Console\Argumentos`, que fornece acesso às opções, valores e argumentos
especificados.

Mais informações sobre argumentos em [usando os argumentos](06-usando-os-argumentos.md).

- [Implementando Opções](05-implementando-opcoes.md)
- [Voltar ao índice](indice.md)
