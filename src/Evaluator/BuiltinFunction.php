<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use Closure;
use MonkeyLang\Object\BuiltinFunctionObject;
use MonkeyLang\Object\MonkeyObject;

final class BuiltinFunction
{
    /** @var array<BuiltinFunctionObject> */
    public static array $store = [];

    public static function set(string $name, Closure $closure): void
    {
        static::$store[$name] = new BuiltinFunctionObject($closure);
    }

    public static function get(string $name): ?MonkeyObject
    {
        return self::$store[$name] ?? null;
    }
}
