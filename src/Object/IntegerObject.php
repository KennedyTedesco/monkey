<?php

declare(strict_types=1);

namespace Monkey\Object;

final class IntegerObject extends MonkeyObject
{
    private int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function type(): int
    {
        return self::MO_INT;
    }

    public function typeLiteral(): string
    {
        return 'INTEGER';
    }

    public function inspect(): string
    {
        return \sprintf('%d', $this->value);
    }
}
