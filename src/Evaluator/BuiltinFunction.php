<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Closure;
use Monkey\Object\BuiltinFunctionObject;
use Monkey\Object\InternalObject;

final class BuiltinFunction
{
    /** @var array<BuiltinFunctionObject> */
    private static array $store;

    public static function set(string $name, Closure $closure): void
    {
        static::$store[$name] = new BuiltinFunctionObject($closure);
    }

    public static function contains(string $name): bool
    {
        return isset(self::$store[$name]);
    }

    public static function get(string $name): InternalObject
    {
        return self::$store[$name];
    }
}
