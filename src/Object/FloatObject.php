<?php

declare(strict_types=1);

namespace Monkey\Object;

use function sprintf;

final readonly class FloatObject extends MonkeyObject
{
    public function __construct(
        public float $value,
    ) {
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

    public function value(): float
    {
        return $this->value;
    }
}
