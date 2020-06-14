<?php

declare(strict_types=1);

namespace Tests;

use Monkey\Object\BooleanObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\InternalObject;
use Monkey\Object\NullObject;

test('eval integer expressions', function (string $input, int $expected) {
    testIntegerObject(evalProgram($input), $expected);
})->with([
    ['5', 5],
    ['10', 10],
    ['(5 + 5) * 2', 20],
    ['5 + 5 * 2', 15],
    ['5 + 5 + 5 + 5 - 10', 10],
    ['2 * 2 * 2 * 2 * 2', 32],
    ['5 * 2 + 10', 20],
    ['5 + 2 * 10', 25],
    ['50 / 2 * 2 + 10', 60],
    ['2 * (5 + 10)', 30],
    ['3 * 3 * 3 + 10', 37],
    ['3 * (3 * 3) + 10', 37],
    ['(5 + 10 * 2 + 15 / 3) * 2 + -10', 50],
    ['-50 + 100 + -50', 0],
    ['20 + 2 * -10', 0],
    ['-5', -5],
    ['-10', -10],
]);

test('eval boolean expression', function (string $input, bool $expected) {
    testBooleanObject(evalProgram($input), $expected);
})->with([
    ['true', true],
    ['false', false],
    ['1 > 1', false],
    ['1 < 1', false],
    ['true == false', false],
    ['true != false', true],
    ['true == true', true],
    ['1 >= 1', true],
    ['1 <= 1', true],
    ['1 == 1', true],
    ['1 == 2', false],
    ['1 != 1', false],
    ['1 != 2', true],
    ['(1 < 2) == true', true],
    ['(1 <= 2) == true', true],
    ['(1 > 2) == true', false],
    ['(1 >= 2) == true', false],
    ['(1 != 2) == true', true],
    ['(1 != 2) != false', true],
]);

test('eval bang operator', function (string $input, bool $expected) {
    testBooleanObject(evalProgram($input), $expected);
})->with([
    ['!true', false],
    ['!false', true],
    ['!5', false],
    ['!!true', true],
]);

test('if else expressions', function (string $input, $expected) {
    if (\is_int($expected)) {
        testIntegerObject(evalProgram($input), $expected);
    } else {
        testNullObject(evalProgram($input), $expected);
    }
})->with([
    ['if (false) { 10 }', NullObject::instance()],
    ['if (0) { 10 }', NullObject::instance()],
    ['if (true == false) { 10 }', NullObject::instance()],
    ['if (true) { 10 }', 10],
    ['if (1) { 10 }', 10],
    ['if (1 < 2) { 10 }', 10],
    ['if (1 > 2) { 10 } else { 20 }', 20],
    ['if (1 < 2) { 10 } else { 20 }', 10],
    ['if (5 * 5 + 10 > 34) { 99 } else { 100 }', 99],
]);

function testIntegerObject(InternalObject $object, int $expected)
{
    assertInstanceOf(IntegerObject::class, $object);
    assertSame(InternalObject::INTEGER_OBJ, $object->type());
    assertSame($expected, $object->value());
}

function testNullObject(InternalObject $object, NullObject $expected)
{
    assertSame($expected, $object);
}

function testBooleanObject(InternalObject $object, bool $expected)
{
    assertInstanceOf(BooleanObject::class, $object);
    assertSame(InternalObject::BOOLEAN_OBJ, $object->type());
    assertSame($expected, $object->value());
}
