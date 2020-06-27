<?php

declare(strict_types=1);

namespace Monkey\Object;

use Closure;

final class BuiltinFunctionObject extends MonkeyObject
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

    public function type(): int
    {
        return self::MO_BUILTIN;
    }

    public function typeLiteral(): string
    {
        return 'BUILTIN_FUNCTION';
    }

    public function inspect(): string
    {
        return 'builtin function';
    }
}
