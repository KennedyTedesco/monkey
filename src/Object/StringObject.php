<?php

declare(strict_types=1);

namespace Monkey\Object;

final readonly class StringObject extends MonkeyObject
{
    public string $value;

    public function __construct(
        string $value,
    ) {
        $this->value = str_replace('\n', "\n", $value);
    }

    public function type(): int
    {
        return self::MO_STRING;
    }

    public function count(): int
    {
        return mb_strlen($this->value);
    }

    public function typeLiteral(): string
    {
        return 'STRING';
    }

    public function inspect(): string
    {
        return $this->value;
    }
}
