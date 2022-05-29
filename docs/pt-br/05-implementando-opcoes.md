# Implementando opções

[◂ Criando Comandos](04-criando-comandos.md) | [Voltar ao índice](indice.md) | [Usando os argumentos ▸](06-usando-os-argumentos.md)
-- | -- | --

## 1. Sobre opções

As opções são a cereja do bolo em um comando. Elas permitem controlar aquilo que o usuário pode fazer ao invocar uma rotina.

As opções são especificadas dentro do método abstrato `Command->initialize()` através do método `Command->addOption()`.

```php
class DizerOla extends Command
{
    protected function initialize(): void
    {
        // ...

        $this->addOption(
            new Option(
                '-l',
                '--ler-arquivo',
                'Lê a mensagem a partir de um arquivo texto',
                Option::REQUIRED | Option::VALUED
            )
        );
    }

    // ...
}
```

Quando uma opção é adicionada (como no exemplo acima), é possível obter seu valor correspondente dentro de `Command->handle()`, usando o método `Arguments::getOption()`.

Por exemplo, se o usuário especificar o seguinte comando:

```bash
./example dizer-ola --ler-arquivo
```

> **Importante:** Todos os valores obtidos pelo objeto Argumentos serão do tipo "string". Mais informações em [Usando Argumentos](06-usando-os-argumentos.md)

```php
class DizerOla extends Command
{
    // ...

    protected function handle(Arguments $arguments): void
    {
        $this->info($arguments->getOption('-l'));
        //  isso exibirá === '1'
    }
}
```

## 2. A assinatura de uma opção

Para criar uma nova opção, pode-se usar até 5 argumentos:

### 2.1. Notação Curta e Notação Longa

São as chaves usadas para interagir com um comando. A curta deve começar com um traço (`-l`) e a longa com dois (`--ler-arquivo`).

Uma das duas notações deverá ser fornecida pelo usuário para ativar a opção.

### 2.2. Descrição

É uma frase explicativa sobre o objetivo da opção. Será usada para exibir na mensagem de ajuda do usuário.

### 2.3. Tipo

É a forma como a opção se comportará na formulação do comando.
Uma opção pode ser de quatro tipos:

#### 2.3.1. Obrigatória

Uma **opção obrigatória** deve ser especificada pelo usuário. Caso contrário, uma mensagem será disparada no terminal, solicitando o preenchimento correto.

```php
new Option(
    '-l',
    '--ler-arquivo',
    'Lê a mensagem a partir de um arquivo texto',
    Option::REQUIRED
)
```

#### 2.3.2. Opcional

Uma **opção opcional** pode ser ignorada, ficando a critério do usuario a especificação dela ou não.

```php
new Option(
    '-l',
    '--ler-arquivo',
    'Lê a mensagem a partir de um arquivo texto',
    Option::OPTIONAL
)
```

#### 2.3.3. Valorada

Uma **opção valorada** exigirá que, após sua chave, o usuário especifique um valor adicional. Por exemplo:

```php
new Option(
    '-l',
    '--ler-arquivo',
    'Lê a mensagem a partir de um arquivo texto',
    Opcao::OPCIONAL | Opcao::COM_VALOR
)
```

```php
new Option(
    '-l',
    '--ler-arquivo',
    'Lê a mensagem a partir de um arquivo texto',
    Opcao::REQUIRED | Opcao::COM_VALOR
)
```

Nos casos acima, o usuário deverá especificar o comando no seguinte formato:

```bash
./example dizer-ola --ler-arquivo "mensagem.txt"
```

#### 2.3.4. Booleana

Uma **opção booleana** é aquela que não exige um valor após a declaração de sua chave. Pode ser tanto opcional como obrigatória.

```php
new Option(
    '-l',
    '--ler-arquivo',
    'Lê a mensagem a partir de um arquivo texto',
    Option::OPTIONAL
)
```

### 2.4. Valor padrão

O valor que a opção passará para o comando, caso o usuário não especifique nenhuma. Este argumento é opcional e funciona apenas com opções do tipo "valoradas".

```php
new Option(
    '-l',
    '--ler-arquivo',
    'Lê a mensagem a partir de um arquivo texto',
    Option::REQUIRED | Option::VALUED,
    "mensagem.txt"
)
```

[◂ Criando Comandos](04-criando-comandos.md) | [Voltar ao índice](indice.md) | [Usando os argumentos ▸](06-usando-os-argumentos.md)
-- | -- | --
