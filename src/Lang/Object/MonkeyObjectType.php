<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Object;

enum MonkeyObjectType: int
{
    case NULL = 0x0;
    case ERROR = 0x1;
    case FLOAT = 0x2;
    case INTEGER = 0x3;
    case BOOL = 0x4;
    case STRING = 0x5;
    case RETURN_VALUE = 0x6;
    case FUNCTION = 0x7;
    case BUILTIN_FUNCTION = 0x8;
    case ARRAY = 0x9;
    case HASH = 0xA;

    public function toString(): string
    {
        return match ($this) {
            self::NULL => 'NULL',
            self::ERROR => 'ERROR',
            self::FLOAT => 'FLOAT',
            self::INTEGER => 'INTEGER',
            self::BOOL => 'BOOL',
            self::STRING => 'STRING',
            self::RETURN_VALUE => 'RETURN_VALUE',
            self::FUNCTION => 'FUNCTION',
            self::BUILTIN_FUNCTION => 'BUILTIN_FUNCTION',
            self::ARRAY => 'ARRAY',
            self::HASH => 'HASH',
        };
    }
}
