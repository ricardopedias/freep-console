# Terminal script

[◂ How to use](01-how-to-use.md) | [Back to index](index.md) | [Instantiating the Terminal ▸](03-instantiating-the-terminal.md)
-- | -- | --

## 1. Creating a script

The purpose of a terminal command is to be executed on the command line, for this obvious reason it is necessary to create a script to receive the arguments passed by the user.

At the root of this repository there is an example script called **"example"**, containing the invocation of the `Freep\Console\Terminal` class:

```php
#!/bin/php
<?php

// Includes Composer autoloader
include __DIR__ . "/vendor/autoload.php";

use Freep\Console\Terminal;

// PHP in CLI mode provides the reserved variable "$argv", containing the list 
// of words typed by the user in the Terminal. This variable will be used to 
// pass the information to the library's Terminal.

// Removes the first argument, which contains the script name (ex: ./example)
array_shift($argv);

$terminal = new Terminal(__DIR__ . "/src");

// other settings for $terminal...

// Use the $argv variable to interpret user arguments
$terminal->run($argv);

```

## 2. Using the terminal

Note that the above script starts with `#!/bin/php`. This notation tells the operating system's terminal that this script should be interpreted by the "/bin/php" program. That way, you don't need to type `php example`, but just
`./example`:

```bash
./example --help
```

> **Note:** on unix or derived systems, to be able to directly invoke a script (eg ./example), it must have the permission to execute. This is achieved by the command `chmod a+x example`

[◂ How to use](01-how-to-use.md) | [Back to index](index.md) | [Instantiating the Terminal ▸](03-instantiating-the-terminal.md)
-- | -- | --
