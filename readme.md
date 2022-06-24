# Freep Console

![PHP Version](https://img.shields.io/badge/php-%5E8.0-blue)
![License](https://img.shields.io/badge/license-MIT-blue)
[![Codacy Badge](https://app.codacy.com/project/badge/Coverage/5a911e53f0cc421282d847d323f50203)](https://www.codacy.com/gh/ricardopedias/freep-console/dashboard?utm_source=github.com&utm_medium=referral&utm_content=ricardopedias/freep-console&utm_campaign=Badge_Coverage)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/5a911e53f0cc421282d847d323f50203)](https://www.codacy.com/gh/ricardopedias/freep-console/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ricardopedias/freep-console&amp;utm_campaign=Badge_Grade)

## Synopsis

This repository contains the necessary functionality to easily implement a terminal command manager in a PHP application.

For detailed information, consult the documentation in [English](docs/en/index.md) or [Portuguese](docs/pt-br/indice.md). See also this readme in [Portuguese](docs/pt-br/leiame.md).

## How to use

### 1. Create a command

Implement a command called "my-command", based on the abstract class `Freep\Console\Command`:

```php
class MyCommand extends Command
{
    protected function initialize(): void
    {
        $this->setName("my-command");
        $this->addOption(
            new Option('-r', '--read', 'Read a text file', Option::REQUIRED)
        );
    }

    protected function handle(Arguments $arguments): void
    {
        $this->info("Hello");
    }
}
```

### 2. Create a script

Create a file, call it for example "myconsole", and add the following content:

```php
#!/bin/php
<?php
include __DIR__ . "/vendor/autoload.php";

array_shift($argv);

$terminal = new Freep\Console\Terminal("/root/of/super/application");
$terminal->loadCommandsFrom("/directory/of/commands");
$terminal->run($argv);
```

### 3. Run the script

```bash
./myconsole my-command -r
# will display: Hello
```

```bash
./myconsole my-command --help
# will display:
#
# Command: my-command
# Run the 'my-command' command
# 
# How to use:
# ./myconsole my-command [options]
# 
# Options:
# -h, --help   Display command help
# -r, --read   Read a text file
```

```bash
./myconsole --help
# will display:
#
# How to use:
# ./myconsole command [options] [arguments]
# 
# Options:
# -h, --help   Display command help
#
# Available commands:
# help           Display command help
# my-command     Run the 'my-command' command
```

## Characteristics

- Made for PHP 8.0 or higher;
- Codified with best practices and maximum quality;
- Well documented and IDE friendly;
- Made with TDD (Test Driven Development);
- Implemented with unit tests using PHPUnit;
- Made with :heart: &amp; :coffee:.

## Summary

- [How to use](docs/en/01-how-to-use.md)
- [Terminal script](docs/en/02-terminal-script.md)
- [Instantiating the Terminal](docs/en/03-instantiating-the-terminal.md)
- [Creating Commands](docs/en/04-creating-commands.md)
- [Implementing Options](docs/en/05-implementing-options.md)
- [Using the arguments](docs/en/06-using-the-arguments.md)
- [Message library](docs/en/07-message-library.md)
- [Testing Commands](docs/en/08-testing-commands.md)
- [Improving the library](docs/en/99-improving-the-library.md)

## Credits

[Ricardo Pereira Dias](https://www.ricardopedias.com.br)
