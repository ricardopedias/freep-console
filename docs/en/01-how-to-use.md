# How to use

[◂ Back to index](index.md) | [Terminal Script ▸](02-terminal-script.md)
-- | --

## 1. Implement commands

The first thing to do is create the necessary commands and place them in some directory. A command must be implemented based on the abstract class `Freep\Console\Command`, as shown in the example below:

```php
class SayHello extends Command
{
    /**
     * At least the "setName" method must be invoked to determine the word
     */
    protected function initialize(): void
    {
        $this->setName("say-hello");
        $this->setDescription("Display 'hello' message in terminal");
        $this->setHowToUse("./example say-hello [options]");

        // A mandatory and valued option.
        // When specified in the terminal, it must be accompanied by a value
        $this->addOption(
            new Option(
                '-r',
                '--read-file',
                'Read the message from a text file',
                Option::REQUIRED | Option::VALUED
            )
        );

        // A non-mandatory option
        $this->addOption(
            new Option(
                '-d',
                '--delete',
                'Delete the text file after using it',
                Option::OPTIONAL
            )
        );
    }

    /**
     * It is in this method that the command routine should be implemented.
     */ 
    protected function handle(Arguments $arguments): void
    {
        $message = "Hello";

        if ($arguments->getOption('-r') !== '1') {
            $this->line("Reading the text file containing the hello message");
            // ... routine to read the text file
            $message = "";
        }

        if ($message === "") {
            $this->error("Could not read text file");
        }

        if ($arguments->getOption('-d') === '1') {
            $this->warning("Deleting the used text file");
            // ... routine to delete the text file
        }

        $this->info($message);
    }
}
```

More information at [Creating Commands](04-creating-commands.md).

## 2. Criando o terminal

With the commands implemented in the desired directory, it is necessary to create an instance of `Freep\Console\Terminal` and tell it which directories contain the implemented commands.

Finally, just tell the Terminal to execute the commands through the `Terminal->run()` method:

```php
// Creates a Terminal instance.
$terminal = new Terminal("root/of/super/application");

// A tip on how the terminal can be used
$terminal->setHowToUse("./example command [options] [arguments]");

// Add two directories containing commands
$terminal->loadCommandsFrom(__DIR__ . "/commands");
$terminal->loadCommandsFrom(__DIR__ . "/more-commands");

// Execute command from a list of arguments
$terminal->run([ "say-hello", "-l", "message.txt", "-d" ]);

```

More information at [Instantiating the Terminal](03-instantiating-the-terminal.md).

[◂ Back to index](index.md) | [Terminal Script ▸](02-terminal-script.md)
-- | --
