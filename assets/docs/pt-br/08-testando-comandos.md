# Testando comandos

--page-nav--

## 1. Usando asserções especiais para PHPUnit

A biblioteca inclui diversas funcionalidades para serem usadas em testes de unidade com o PHPUnit. Basta estender a classe `Freep\Console\Tests\ConsoleTestCase` ao invés da `PHPUnit\Framework\TestCase`.

## 2. Asserções disponíveis

As seguintes asserções adicionais estarão disponíveis para uso:

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
