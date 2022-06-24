# Improving the library

[◂ Testing Commands](08-testing-commands.md) | [Back to index](index.md)
-- | --

## 1. Infrastructure

If [Docker](https://www.docker.com/) is installed on your computer, it is not necessary to have Composer or PHP installed.

To use Composer and the code quality libraries, use the `./composer` script, located at the root of this repository. This script is actually a bridge to all Composer commands, running them through Docker.

## 2. Quality control

### 2.1. Tools

For development, tools for unit testing and static analysis were used. All configured to the maximum level of demand.

These are the following tools:

- [PHP Unit](https://phpunit.de)
- [PHP Stan](https://phpstan.org)
- [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [PHP MD](https://phpmd.org)

### 2.2. Static analysis

To analyze the implemented code and gather feedback from the tools, use:

```bash
./composer analyse
```

### 2.3. Automated tests

To run the unit tests, use:

```bash
./composer test
```

[◂ Testing Commands](08-testing-commands.md) | [Back to index](index.md)
-- | --
