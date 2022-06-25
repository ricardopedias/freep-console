<?php

declare(strict_types=1);

namespace Freep\Console\PhpUnit;

use Freep\Console\Command;
use Freep\Console\Option;
use Freep\Console\PhpUnit\Constraints\CommandCountOptions;
use Freep\Console\PhpUnit\Constraints\CommandHasName;
use Freep\Console\PhpUnit\Constraints\CommandHasDescription;
use Freep\Console\PhpUnit\Constraints\CommandHasHowToUse;
use Freep\Console\PhpUnit\Constraints\CommandHasOption;
use Freep\Console\PhpUnit\Constraints\OptionHasDefaultValue;
use Freep\Console\PhpUnit\Constraints\OptionHasDescription;
use Freep\Console\PhpUnit\Constraints\OptionHasLongNotation;
use Freep\Console\PhpUnit\Constraints\OptionHasShortNotation;
use Freep\Console\PhpUnit\Constraints\OptionIsBoolean;
use Freep\Console\PhpUnit\Constraints\OptionIsRequired;
use Freep\Console\PhpUnit\Constraints\OptionIsValued;
use Freep\Console\PhpUnit\Constraints\OptionIsNotBoolean;
use Freep\Console\PhpUnit\Constraints\OptionIsNotRequired;
use Freep\Console\PhpUnit\Constraints\OptionIsNotValued;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ConsoleTestCase extends TestCase
{
    public static function assertCommandHasName(
        string $name,
        Command $command,
        string $message = ''
    ): void {
        $constraint = new CommandHasName($name);
        self::assertThat($command, $constraint, $message);
    }

    public static function assertCommandHasDescription(
        string $name,
        Command $command,
        string $message = ''
    ): void {
        $constraint = new CommandHasDescription($name);
        self::assertThat($command, $constraint, $message);
    }

    public static function assertCommandHasHowToUse(
        string $name,
        Command $command,
        string $message = ''
    ): void {
        $constraint = new CommandHasHowToUse($name);
        self::assertThat($command, $constraint, $message);
    }

    public static function assertCommandHasOption(
        string $notation,
        Command $command,
        string $message = ''
    ): void {
        $constraint = new CommandHasOption($notation);
        self::assertThat($command, $constraint, $message);
    }

    public static function assertCountCommandOptions(
        int $amount,
        Command $command,
        string $message = ''
    ): void {
        $constraint = new CommandCountOptions($amount);
        self::assertThat($command, $constraint, $message);
    }

    public static function assertOptionHasShortNotation(
        string $notation,
        Option $option,
        string $message = ''
    ): void {
        $constraint = new OptionHasShortNotation($notation);
        self::assertThat($option, $constraint, $message);
    }

    public static function assertOptionHasLongNotation(
        string $notation,
        Option $option,
        string $message = ''
    ): void {
        $constraint = new OptionHasLongNotation($notation);
        self::assertThat($option, $constraint, $message);
    }

    public static function assertOptionHasDescription(
        string $description,
        Option $option,
        string $message = ''
    ): void {
        $constraint = new OptionHasDescription($description);
        self::assertThat($option, $constraint, $message);
    }

    public static function assertOptionHasDefaultValue(
        string $value,
        Option $option,
        string $message = ''
    ): void {
        $constraint = new OptionHasDefaultValue($value);
        self::assertThat($option, $constraint, $message);
    }

    public static function assertOptionIsBoolean(
        Option $option,
        string $message = ''
    ): void {
        $constraint = new OptionIsBoolean();
        self::assertThat($option, $constraint, $message);
    }

    public static function assertOptionIsRequired(
        Option $option,
        string $message = ''
    ): void {
        $constraint = new OptionIsRequired();
        self::assertThat($option, $constraint, $message);
    }

    public static function assertOptionIsValued(
        Option $option,
        string $message = ''
    ): void {
        $constraint = new OptionIsValued();
        self::assertThat($option, $constraint, $message);
    }

    public static function assertOptionIsNotBoolean(
        Option $option,
        string $message = ''
    ): void {
        $constraint = new OptionIsNotBoolean();
        self::assertThat($option, $constraint, $message);
    }

    public static function assertOptionIsNotRequired(
        Option $option,
        string $message = ''
    ): void {
        $constraint = new OptionIsNotRequired();
        self::assertThat($option, $constraint, $message);
    }

    public static function assertOptionIsNotValued(
        Option $option,
        string $message = ''
    ): void {
        $constraint = new OptionIsNotValued();
        self::assertThat($option, $constraint, $message);
    }
}
