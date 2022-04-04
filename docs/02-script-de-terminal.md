# Freep Console

[Voltar ao índice](indice.md)

## Script de terminal

O objetivo de um comando de terminal é ser executado no terminal, por esse motivo óbvio é 
preciso criar um script para receber os comandos.

Na raiz deste repositório existe um script de exemplo chamado "superapp", contendo a invocação da classe Freep\Console\Terminal:

```php
#!/bin/php
<?php

// Carrega o autoloader do Composer
include __DIR__ . "/vendor/autoload.php";

use Freep\Console\Terminal;

// O PHP em modo CLI disponibiliza a variável reservada "$argv", contendo a lista 
// de palavras digitadas pelo usuário no Terminal. Esta variável será usada para
// passar as informações para o Terminal da biblioteca.

// Remove o primeiro argumento, que contém o nome do script (ex: ./superapp)
array_shift($argv);

$terminal = new Terminal(__DIR__ . "/codigo");
// ...

// Usa a variável $argv para interpretar os argumentos do usuário
$terminal->executar($argv);

```

## Usando o terminal

Perceba que o script acima inicia com `#!/bin/php`. Essa notação diz para 
o terminal do sistema operacional que este script deverá ser interpretado pelo 
programa "/bin/php". Dessa forma, não é necessário digitar `php superapp`, mas apenas
`./superapp`:

```bash
$ ./superapp --ajuda
```
[Voltar ao índice](indice.md)