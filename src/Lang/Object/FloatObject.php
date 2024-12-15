<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Object;

use function sprintf;

final readonly class FloatObject extends MonkeyObject
{
    public function __construct(
        public float $value,
    ) {
    }

    public function type(): MonkeyObjectType
    {
        return MonkeyObjectType::FLOAT;
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
