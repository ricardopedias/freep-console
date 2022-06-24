# Using arguments

[◂ Implementing Options](05-implementing-options.md) | [Back to index](index.md) | [Message library ▸](07-message-library.md)
-- | -- | --

## 1. The Arguments object

The Arguments object is created by the command parser, after scanning the options provided by the user.

It contains all the options typed in the terminal, organized according to their context.

Consider the following command typed in the terminal:

```bash
./example say-hello -n "Ricardo Pereira" --book "Arquitetura Limpa" Test 'Portas e Adaptadores' --dev
```

The interpreter will send to the `Command->handle()` method, an `Arguments` object, where options can be obtained to implement the command routine.

There are two types of arguments:

### 1.1. Argument with flag

These are the option values determined within the abstract method `Command->initialize()` via the `Command->addOption()` method.

For example:

```bash
./example say-hello -n "Ricardo Pereira" -l "Arquitetura Limpa" Test 'Portas e Adaptadores' -d
```

```php
class SayHello extends Command
{
    // ...

    protected function handle(Arguments $arguments): void
    {
        $this->info($arguments->getOption('-n'));
        //  this will display: Ricardo Pereira

        $this->info($arguments->getOption('-r'));
        //  this will display: Arquitetura Limpa

        $this->info($arguments->getOption('-d'));
        //  this will display: 1
    }
}
```

### 1.2. Standalone argument

These are single values, specified within the command line.
These values do not belong to any valid options.

For example:

```bash
./example say-hello -n "Ricardo Pereira" -l "Arquitetura Limpa" Test 'Portas e Adaptadores' -d
```

```php
class SayHello extends Command
{
    // ...

    protected function handle(Arguments $arguments): void
    {
        $this->info($arguments->getArgument(0));
        //  this will display: Teste

        $this->info($arguments->getArgument(1));
        //  this will display: Portas e Adaptadores
    }
}
```

## 2. Argument values

All values obtained by the `Arguments` object will be of type "string", no matter if they are texts or numbers. If it is a boolean, the string returned will be "0" or "1".

[◂ Implementing Options](05-implementing-options.md) | [Back to index](index.md) | [Message library ▸](07-message-library.md)
-- | -- | --
