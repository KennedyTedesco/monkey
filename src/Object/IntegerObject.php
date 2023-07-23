<?php

declare(strict_types=1);

namespace Monkey\Object;

final readonly class IntegerObject extends MonkeyObject
{
    public function __construct(
        public int $value,
    ) {
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
        return sprintf('%d', $this->value);
    }

    public function value(): int
    {
        return $this->value;
    }
}
