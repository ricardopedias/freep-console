# Usando argumentos

- [Voltar ao índice](indice.md)
- [Implementando Opções](05-implementando-opcoes.md)

## 1. Objeto Argumentos

O objeto Argumentos é criado pelo interpretador de comandos, após varrer as opções fornecidas pelo usuário.

Ele contém todas todas as opções digitadas no terminal, organizadas de acordo com seu contexto.

Considere o seguinte comando digitado no terminal:

```bash
$ ./superapp dizer-ola -n "Ricardo Pereira" --livro "Arquitetura Limpa" Teste 'Portas e Adaptadores' --dev
```

O interpretador vai enviar para o método `Comando->manipular()`, um objeto `Argumentos`, onde as opções poderão ser obtidas para implementar a rotina do comando.

Existem dois dipos de argumentos:

### 1.1. Argumento com chave

São os valores das opções determinadas dentro do método abstrato `Comando->inicializar()` através do método `Comando->adicionarOpcao()`. 

Por exemplo: 

```bash
$ ./superapp dizer-ola -n "Ricardo Pereira" -l "Arquitetura Limpa" Teste 'Portas e Adaptadores' -d
```

```php
class DizerOla extends Comando
{
    // ...

    protected function manipular(Argumentos $argumentos): void
    {
        $this->info($argumentos->opcao('-n'));
        //  isso exibirá: Ricardo Pereira

        $this->info($argumentos->opcao('-l'));
        //  isso exibirá: Arquitetura Limpa

        $this->info($argumentos->opcao('-d'));
        //  isso exibirá: 1
    }
}
```

### 1.2. Argumento sem chave

São valores avulsos, especificados dentro da linha de comandos. 
Esses valores não pertencem a nenhuma opção válida.

Por exemplo: 

```bash
$ ./superapp dizer-ola -n "Ricardo Pereira" -l "Arquitetura Limpa" Teste 'Portas e Adaptadores' -d
```

```php
class DizerOla extends Comando
{
    // ...

    protected function manipular(Argumentos $argumentos): void
    {
        $this->info($argumentos->argumento(0));
        //  isso exibirá: Teste

        $this->info($argumentos->argumento(1));
        //  isso exibirá: Portas e Adaptadores
    }
}
```

## 2. Valores dos argumentos

Todos os valores obtidos pelo objeto `Argumentos` serão do tipo "string", não importa se sejam textos ou números. Caso seja um booleano, a string devolvida será "0" ou "1". 



- [Evoluindo a biblioteca](07-evoluindo-a-biblioteca.md)
- [Voltar ao índice](indice.md)
