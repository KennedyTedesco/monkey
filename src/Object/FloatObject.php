<?php

declare(strict_types=1);

namespace Monkey\Object;

final class FloatObject implements InternalObject
{
    private float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public function value(): float
    {
        return $this->value;
    }

    public function type(): string
    {
        return self::FLOAT_OBJ;
    }

    public function inspect(): string
    {
        return \sprintf('%f', $this->value);
    }
}
