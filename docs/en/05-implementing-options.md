# Implementing Options

[◂ Creating Commands](04-creating-commands.md) | [Back to index](index.md) | [Using the arguments ▸](06-using-the-arguments.md)
-- | -- | --

## 1. About options

Options are the 'icing on the cake' in one command. They allow you to control what the user can do when invoking a routine.

Options are specified within the abstract method `Command->initialize()` via the `Command->addOption()` method.

```php
class SayHello extends Command
{
    protected function initialize(): void
    {
        // ...

        $this->addOption(
            new Option(
                '-r',
                '--read',
                'Read the message from a text file',
                Option::REQUIRED | Option::VALUED
            )
        );
    }

    // ...
}
```

When an option is added (as in the example above), it is possible to get its corresponding value inside `Command->handle()`, using the `Arguments::getOption()` method.

For example, if the user specifies the following command:

```bash
./example say-hello --read
```

> **Important:** All values obtained by the Arguments object will be of type "string". More information at [Using the arguments](06-using-the-arguments.md)

```php
class SayHello extends Command
{
    // ...

    protected function handle(Arguments $arguments): void
    {
        $this->info($arguments->getOption('-r'));
        //  this will display '1'
    }
}
```

## 2. Signature of an option

To create a new option, you can use up to 5 arguments:

### 2.1. Short Notation and Long Notation

These are the keys used to interact with a command. The short must begin with a dash (`-r`) and the long one with two dashes (`--read`).

At least one of the two notations must be provided by the user to activate the option.

### 2.2. Description

It is an explanatory sentence about the purpose of the option. It will be used to display in the user's help message.

### 2.3. Type

It is the way the option will behave in the command formulation.
An option can be of four types:

#### 2.3.1. Required

A **mandatory option** must be specified by the user. Otherwise, a message will be triggered in the terminal, asking for the correct filling.

```php
new Option(
    '-r',
    '--read',
    'Read the message from a text file',
    Option::REQUIRED
)
```

#### 2.3.2. Optional

An **non-mandatory option** can be ignored, leaving it up to the user to specify it or not.

```php
new Option(
    '-r',
    '--read',
    'Read the message from a text file',
    Option::OPTIONAL
)
```

#### 2.3.3. Valued

A **valued option** will require the user to specify an additional value after its key. For example:

```php
new Option(
    '-r',
    '--read',
    'Read the message from a text file',
    Option::OPTIONAL | Option::VALUED
)
```

```php
new Option(
    '-r',
    '--read',
    'Read the message from a text file',
    Option::REQUIRED | Option::VALUED
)
```

In the above cases, the user must specify the command in the following format:

```bash
./example say-hello --read "message.txt"
```

#### 2.3.4. Boolean

A **boolean option** is one that does not require a value after declaring its key. It can be either optional or mandatory.

```php
new Option(
    '-r',
    '--read',
    'Read the message from a text file',
    Option::OPTIONAL
)
```

### 2.4. Default value

The value that the option will pass to the command if the user does not specify any. This argument is optional and only works with "valued" options.

```php
new Option(
    '-r',
    '--read',
    'Read the message from a text file',
    Option::REQUIRED | Option::VALUED,
    "message.txt"
)
```

[◂ Creating Commands](04-creating-commands.md) | [Back to index](index.md) | [Using the arguments ▸](06-using-the-arguments.md)
-- | -- | --
