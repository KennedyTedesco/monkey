<?php

declare(strict_types=1);

namespace Monkey\Object;

final readonly class FloatObject extends MonkeyObject
{
    public function __construct(
        private float $value,
    ) {
    }

    public function value(): float
    {
        return $this->value;
    }

    public function type(): int
    {
        return self::MO_FLOAT;
    }

    public function typeLiteral(): string
    {
        return 'FLOAT';
    }

    public function inspect(): string
    {
        return sprintf('%f', $this->value);
    }
}
