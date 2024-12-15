<?php

declare(strict_types=1);

namespace MonkeyLang\Object;

final readonly class StringObject extends MonkeyObject
{
    public string $value;

    public function __construct(
        string $value,
    ) {
        $this->value = str_replace('\n', "\n", $value);
    }

    public function type(): MonkeyObjectType
    {
        return MonkeyObjectType::STRING;
    }

    public function count(): int
    {
        return mb_strlen($this->value);
    }

    public function inspect(): string
    {
        return $this->value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
