<?php

declare(strict_types=1);

namespace Tests;

use Monkey\Object\BooleanObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\InternalObject;

test('eval integer expression', function (string $input, int $expected) {
    testIntegerObject(evalProgram($input), $expected);
})->with([
    ['10', 10],
    ['100', 100],
    ['1000', 1000],
    ['10000', 10000],
    ['100000', 100000],
    ['1000000', 1000000],
    [(string) \PHP_INT_MAX, \PHP_INT_MAX],
]);

test('eval boolean expression', function (string $input, bool $expected) {
    testBooleanObject(evalProgram($input), $expected);
})->with([
    ['true', true],
    ['false', false],
]);

function testIntegerObject(InternalObject $object, int $expected)
{
    assertInstanceOf(IntegerObject::class, $object);
    assertSame(InternalObject::INTEGER_OBJ, $object->type());
    assertSame($expected, $object->value());
}

function testBooleanObject(InternalObject $object, bool $expected)
{
    assertInstanceOf(BooleanObject::class, $object);
    assertSame(InternalObject::BOOLEAN_OBJ, $object->type());
    assertSame($expected, $object->value());
}
