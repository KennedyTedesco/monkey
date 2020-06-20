<?php

declare(strict_types=1);

namespace Monkey\Object;

use Closure;

final class BuiltinFunctionObject implements InternalObject
{
    private Closure $function;

    public function __construct(Closure $function)
    {
        $this->function = $function;
    }

    public function value(): Closure
    {
        return $this->function;
    }

    public function type(): string
    {
        return self::BUILTIN_OBJ;
    }

    public function inspect(): string
    {
        return 'builtin function';
    }
}
