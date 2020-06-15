<?php

declare(strict_types=1);

namespace Monkey\Object;

interface InternalObject
{
    public const NULL_OBJ = 'NULL';
    public const ERROR_OBJ = 'ERROR';

    public const INTEGER_OBJ = 'INTEGER';
    public const BOOLEAN_OBJ = 'BOOLEAN';
    public const STRING_OBJ = 'STRING';

    public const RETURN_VALUE_OBJ = 'RETURN_VALUE';

    public const FUNCTION_OBJ = 'FUNCTION';
    public const BUILTIN_OBJ = 'BUILTIN';

    public const ARRAY_OBJ = 'ARRAY';
    public const HASH_OBJ = 'HASH';

    public function type(): string;

    public function inspect(): string;
}
