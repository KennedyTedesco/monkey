<?php

declare(strict_types=1);

namespace Monkey\Object;

abstract readonly class MonkeyObject
{
    /** @var int */
    public const MO_NULL = 0x0;

    /** @var int */
    public const MO_ERROR = 0x1;

    /** @var int */
    public const MO_FLOAT = 0x2;

    /** @var int */
    public const MO_INT = 0x3;

    /** @var int */
    public const MO_BOOL = 0x4;

    /** @var int */
    public const MO_STRING = 0x5;

    /** @var int */
    public const MO_RETURN_VALUE = 0x6;

    /** @var int */
    public const MO_FUNCTION = 0x7;

    /** @var int */
    public const MO_BUILTIN = 0x8;

    /** @var int */
    public const MO_ARRAY = 0x9;

    /** @var int */
    public const MO_HASH = 0xA;

    abstract public function type(): int;

    abstract public function value(): mixed;

    abstract public function inspect(): string;

    abstract public function typeLiteral(): string;
}
