<?php

declare(strict_types=1);

namespace Monkey\Object;

final class StringObject extends MonkeyObject
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function type(): int
    {
        return self::MO_STRING;
    }

    public function typeLiteral(): string
    {
        return 'STRING';
    }

    public function inspect(): string
    {
        return \sprintf('%s', $this->value);
    }
}
