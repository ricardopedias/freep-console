# Instantiating the Terminal

[◂ Terminal script](02-terminal-script.md) | [Back to index](index.md) | [Creating Commands ▸](04-creating-commands.md)
-- | -- | --

## 1. Implementation

The interpretation of the arguments entered by the user happens through the instance of the `Freep\Console\Terminal` class, which can be configured as follows:

```php
$terminal = new Terminal(__DIR__ . "/src");
$terminal->setHowToUse("./example command [options] [arguments]");
$terminal->loadCommandsFrom(__DIR__ . "/tests/FakeApp/ContextOne/src/Commands");
$terminal->loadCommandsFrom(__DIR__ . "/tests/FakeApp/ContextTwo");

$terminal->run($argv);
```

## 2. Available methods

### 2.1. The working directory

```php
$terminal = new Terminal(__DIR__ . "/src");
```

The instance of `Freep\Console\Terminal` must be created, specifying a **"working directory"**.
This directory will effectively not cause any side effects.

It's just a way to make available, for all existing commands, what is the *"main directory"* of the current project.

Generally, the **"working directory"** will be the root directory of the application that will use the library to interpret its commands. That way, the commands will be able to know where the project structure is.

### 2.2. How to use

```php
$terminal->setHowToUse("./example command [options] [arguments]");
```

Specifies the help message about the command format. Note that it takes into account the name of the current script, ie `example`.

### 2.3. Command directory

```php
$terminal->loadCommandsFrom(__DIR__ . "/tests/FakeApp/ContextOne/src/Commands");
$terminal->loadCommandsFrom(__DIR__ . "/tests/FakeApp/ContextTwo");
```

Numerous directories containing commands can be specified. Each will be scanned through the library to identify available commands.

When the user types `./example --help`, the help information for all commands will be used to display a comprehensive help screen in the user's terminal.

### 2.4. Interpret user input

```php
$terminal->run($argv);
```

Arguments typed by the user in the operating system's terminal are interpreted here, using the PHP reserved variable called "$argv". It contains a list of words typed in the terminal and is only present when a PHP script is executed in CLI, that is, in the terminal.

More information from the PHP documentation at [Reserved Variables](https://www.php.net/manual/pt_BR/reserved.variables.argv.php)

[◂ Terminal script](02-terminal-script.md) | [Back to index](index.md) | [Creating Commands ▸](04-creating-commands.md)
-- | -- | --
