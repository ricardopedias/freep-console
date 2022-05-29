# Script de terminal

[◂ Modo de Usar](01-modo-de-usar.md) | [Voltar ao índice](indice.md) | [Instanciando o terminal ▸](03-instanciando-o-terminal.md)
-- | -- | --

## 1. Criando um script

O objetivo de um comando de terminal é ser executado na linha de comando, por esse motivo óbvio é preciso criar um script para receber os argumentos passados pelo usuário.

Na raiz deste repositório existe um script de exemplo chamado **"example"**, contendo a invocação da classe `Freep\Console\Terminal`:

```php
#!/bin/php
<?php

// Carrega o autoloader do Composer
include __DIR__ . "/vendor/autoload.php";

use Freep\Console\Terminal;

// O PHP em modo CLI disponibiliza a variável reservada "$argv", contendo a lista 
// de palavras digitadas pelo usuário no Terminal. Esta variável será usada para
// passar as informações ao Terminal da biblioteca.

// Remove o primeiro argumento, que contém o nome do script (ex: ./superapp)
array_shift($argv);

$terminal = new Terminal(__DIR__ . "/src");

// outras configurações para o $terminal ...

// Usa a variável $argv para interpretar os argumentos do usuário
$terminal->run($argv);

```

## 2. Usando o terminal

Perceba que o script acima inicia com `#!/bin/php`. Essa notação diz para o terminal do sistema operacional que este script deverá ser interpretado pelo programa "/bin/php". Dessa forma, não é necessário digitar `php example`, mas apenas `./example`:

```bash
./example --help
```

> **Nota:** em sistemas unix ou derivados, para poder invocar diretamente um script (ex: ./example), é preciso que ele possua permissão para executar. Isso é conseguido pelo comando `chmod a+x example`

[◂ Modo de Usar](01-modo-de-usar.md) | [Voltar ao índice](indice.md) | [Instanciando o terminal ▸](03-instanciando-o-terminal.md)
-- | -- | --
