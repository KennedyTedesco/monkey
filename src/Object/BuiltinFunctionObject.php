<?php

declare(strict_types=1);

namespace Monkey\Object;

use Closure;

final readonly class BuiltinFunctionObject extends MonkeyObject
{
    public function __construct(
        private Closure $function,
    ) {
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
