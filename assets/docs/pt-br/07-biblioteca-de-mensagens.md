# Evoluindo a biblioteca

--page-nav--

## 1. A biblioteca de mensagens

Além das funcionalidades exclusivas para criação e execução de comandos, freep-console contém uma classe dedicada para a exibição de mensagens no terminal.

Abaixo, os métodos disponíveis na classe `Freep\Console\Message`:

```php
$message = new Message('thundercats');

// mensagem azul
$message->blue();
$message->blueLn(); // com quebra de linha

// mensagem verde
$message->green();
$message->greenLn(); // com quebra de linha

// mensagem vermelha
$message->red();
$message->redLn(); // com quebra de linha

// mensagem amarela
$message->yellow();
$message->yellowLn(); // com quebra de linha

// mensagem de erro (com ícone ✗)
$message->error();
$message->errorLn(); // com quebra de linha

// mensagem de informação (com ícone ➜)
$message->info();
$message->infoLn(); // com quebra de linha

// mensagem de sucesso (com ícone ✔)
$message->success();
$message->successLn(); // com quebra de linha

// mensagem de alerta (com ícone ✱)
$message->warning();
$message->warningLn(); // com quebra de linha

// mensagem comum
$message->output();
$message->outputLn(); // com quebra de linha
```

--page-nav--
