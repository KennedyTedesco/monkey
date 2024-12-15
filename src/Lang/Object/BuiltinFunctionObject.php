<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Object;

use Closure;

final readonly class BuiltinFunctionObject extends MonkeyObject
{
    public function __construct(
        public Closure $value,
    ) {
    }

    public function type(): MonkeyObjectType
    {
        return MonkeyObjectType::BUILTIN_FUNCTION;
    }

    public function inspect(): string
    {
        return 'builtin function';
    }

    public function value(): Closure
    {
        return $this->value;
    }
}
