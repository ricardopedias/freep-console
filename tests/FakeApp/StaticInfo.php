<?php

declare(strict_types=1);

namespace Tests\FakeApp;

class StaticInfo
{
    private static ?StaticInfo $instance = null;

    /** @var array<string,mixed> */
    private array $data = [];

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /** @param mixed $value */
    public function addData(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    /** @return mixed */
    public function getData(string $name)
    {
        if (isset($this->data[$name]) === false) {
            return null;
        }

        return $this->data[$name];
    }

    public function clearData(): void
    {
        $this->data = [];
    }
}
