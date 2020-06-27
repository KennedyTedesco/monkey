<?php

declare(strict_types=1);

namespace Monkey\Object;

abstract class MonkeyObject
{
    public const MO_NULL = 0x0;
    public const MO_ERROR = 0x1;
    public const MO_FLOAT = 0x2;
    public const MO_INT = 0x3;
    public const MO_BOOL = 0x4;
    public const MO_STRING = 0x5;
    public const MO_RETURN_VALUE = 0x6;
    public const MO_FUNCTION = 0x7;
    public const MO_BUILTIN = 0x8;
    public const MO_ARRAY = 0x9;
    public const MO_HASH = 0xA;

    abstract public function type(): int;

    abstract public function inspect(): string;

    abstract public function typeLiteral(): string;

    public function isInt(): bool
    {
        return self::MO_INT === $this->type();
    }

    public function isFloat(): bool
    {
        return self::MO_FLOAT === $this->type();
    }

    public function isString(): bool
    {
        return self::MO_STRING === $this->type();
    }

    public function isArray(): bool
    {
        return self::MO_ARRAY === $this->type();
    }

    public function isBool(): bool
    {
        return self::MO_BOOL === $this->type();
    }
}
