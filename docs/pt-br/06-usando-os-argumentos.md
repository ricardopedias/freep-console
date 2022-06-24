# Usando argumentos

[◂ Implementando Opções](05-implementando-opcoes.md) | [Voltar ao índice](indice.md) | [Biblioteca de mensagens ▸](07-biblioteca-de-mensagens.md)
-- | -- | --

## 1. Objeto Argumentos

O objeto Argumentos é criado pelo interpretador de comandos, após varrer as opções fornecidas pelo usuário.

Ele contém todas todas as opções digitadas no terminal, organizadas de acordo com seu contexto.

Considere o seguinte comando digitado no terminal:

```bash
./example dizer-ola -n "Ricardo Pereira" --livro "Arquitetura Limpa" Teste 'Portas e Adaptadores' --dev
```

O interpretador vai enviar para o método `Command->handle()`, um objeto `Arguments`, onde as opções poderão ser obtidas para implementar a rotina do comando.

Existem dois dipos de argumentos:

### 1.1. Argumento com chave

São os valores das opções determinadas dentro do método abstrato `Command->initialize()` através do método `Command->addOption()`.

Por exemplo:

```bash
./example dizer-ola -n "Ricardo Pereira" -l "Arquitetura Limpa" Teste 'Portas e Adaptadores' -d
```

```php
class DizerOla extends Command
{
    // ...

    protected function handle(Arguments $arguments): void
    {
        $this->info($arguments->getOption('-n'));
        //  isso exibirá: Ricardo Pereira

        $this->info($arguments->getOption('-r'));
        //  isso exibirá: Arquitetura Limpa

        $this->info($arguments->getOption('-d'));
        //  isso exibirá: 1
    }
}
```

### 1.2. Argumento sem chave

São valores avulsos, especificados dentro da linha de comandos.
Esses valores não pertencem a nenhuma opção válida.

Por exemplo:

```bash
./example dizer-ola -n "Ricardo Pereira" -l "Arquitetura Limpa" Teste 'Portas e Adaptadores' -d
```

```php
class DizerOla extends Command
{
    // ...

    protected function handle(Arguments $arguments): void
    {
        $this->info($arguments->getArgument(0));
        //  isso exibirá: Teste

        $this->info($arguments->getArgument(1));
        //  isso exibirá: Portas e Adaptadores
    }
}
```

## 2. Valores dos argumentos

Todos os valores obtidos pelo objeto `Arguments` serão do tipo "string", não importa se sejam textos ou números.Caso seja um booleano, a string devolvida será "0" ou "1".

[◂ Implementando Opções](05-implementando-opcoes.md) | [Voltar ao índice](indice.md) | [Biblioteca de mensagens ▸](07-biblioteca-de-mensagens.md)
-- | -- | --
