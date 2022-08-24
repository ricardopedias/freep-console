# Testing Commands

--page-nav--

## 1. Using special assertions for PHPUnit

The library includes several features to be used in unit tests with PHPUnit. Just extend the `Freep\Console\Tests\ConsoleTestCase` class instead of `PHPUnit\Framework\TestCase`.

## 2. Available assertions

The following additional assertions will be available for use:

- assertCommandHasName
- assertCommandHasDescription
- assertCommandHasHowToUse
- assertCommandHasOption
- assertCountCommandOptions
- assertOptionHasShortNotation
- assertOptionHasLongNotation
- assertOptionHasDescription
- assertOptionHasDefaultValue
- assertOptionIsBoolean
- assertOptionIsRequired
- assertOptionIsValued
- assertOptionIsNotBoolean
- assertOptionIsNotRequired
- assertOptionIsNotValued

--page-nav--
