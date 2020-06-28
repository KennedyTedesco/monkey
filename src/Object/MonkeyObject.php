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
    public const MO_OUTPUT = 0xB;

    abstract public function type(): int;

    abstract public function inspect(): string;

    abstract public function typeLiteral(): string;
}
