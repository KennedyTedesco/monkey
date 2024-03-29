<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Closure;
use Monkey\Object\BuiltinFunctionObject;
use Monkey\Object\MonkeyObject;

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
