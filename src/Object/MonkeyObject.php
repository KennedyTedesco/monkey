<?php

declare(strict_types=1);

namespace Monkey\Object;

abstract class MonkeyObject
{
    public const NULL_OBJ = 'NULL';
    public const ERROR_OBJ = 'ERROR';
    public const FLOAT_OBJ = 'FLOAT';
    public const INTEGER_OBJ = 'INTEGER';
    public const BOOLEAN_OBJ = 'BOOLEAN';
    public const STRING_OBJ = 'STRING';
    public const RETURN_VALUE_OBJ = 'RETURN_VALUE';
    public const FUNCTION_OBJ = 'FUNCTION';
    public const BUILTIN_OBJ = 'BUILTIN';
    public const ARRAY_OBJ = 'ARRAY';
    public const HASH_OBJ = 'HASH';

    abstract public function type(): string;

    abstract public function inspect(): string;

    public function isInt(): bool
    {
        return self::INTEGER_OBJ === $this->type();
    }

    public function isFloat(): bool
    {
        return self::FLOAT_OBJ === $this->type();
    }

    public function isString(): bool
    {
        return self::STRING_OBJ === $this->type();
    }

    public function isArray(): bool
    {
        return self::ARRAY_OBJ === $this->type();
    }

    public function isBool(): bool
    {
        return self::BOOLEAN_OBJ === $this->type();
    }
}
