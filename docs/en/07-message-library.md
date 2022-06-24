# Improving the library

[◂ Using the arguments](06-using-the-arguments.md) | [Back to index](index.md) | [Testing Commands ▸](08-testing-commands.md)
-- | -- | --

## 1. A biblioteca de mensagens

In addition to the exclusive functionality for creating and executing commands, freep-console contains a dedicated class for displaying messages in the terminal.

Below are the methods available in the `Freep\Console\Message` class:

```php
$message = new Message('thundercats');

// blue message
$message->blue();
$message->blueLn(); // with line break

// green message
$message->green();
$message->greenLn(); // with line break

// red message
$message->red();
$message->redLn(); // with line break

// yellow message
$message->yellow();
$message->yellowLn(); // with line break

// error message (with icon ✗)
$message->error();
$message->errorLn(); // with line break

// information message (with icon ➜)
$message->info();
$message->infoLn(); // with line break

// success message (with icon ✔)
$message->success();
$message->successLn(); // with line break

// warning message (with icon ✱)
$message->warning();
$message->warningLn(); // with line break

// common message
$message->output();
$message->outputLn(); // with line break
```

[◂ Using the arguments](06-using-the-arguments.md) | [Back to index](index.md) | [Testing Commands ▸](08-testing-commands.md)
-- | -- | --
