<?php

declare(strict_types=1);

namespace Tests;

use Monkey\Object\ArrayObject;
use Monkey\Object\BooleanObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\FloatObject;
use Monkey\Object\FunctionObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;
use Monkey\Object\StringObject;

function testIntegerObject(MonkeyObject $object, int $expected)
{
    assertInstanceOf(IntegerObject::class, $object);
    assertSame(MonkeyObject::MO_INT, $object->type());
    assertSame($expected, $object->value());
}

function testStringObject(MonkeyObject $object, string $expected)
{
    assertInstanceOf(StringObject::class, $object);
    assertSame(MonkeyObject::MO_STRING, $object->type());
    assertSame($expected, $object->value());
}

function testFloatObject(MonkeyObject $object, float $expected)
{
    assertInstanceOf(FloatObject::class, $object);
    assertSame(MonkeyObject::MO_FLOAT, $object->type());
    assertSame($expected, $object->value());
}

function testNullObject(MonkeyObject $object, NullObject $expected)
{
    assertSame($expected, $object);
}

function testBooleanObject(MonkeyObject $object, bool $expected)
{
    assertInstanceOf(BooleanObject::class, $object);
    assertSame(MonkeyObject::MO_BOOL, $object->type());
    assertSame($expected, $object->value());
}

test('eval integer expressions', function (string $input, $expected) {
    if (\is_int($expected)) {
        testIntegerObject(evalProgram($input), $expected);
    } elseif (\is_float($expected)) {
        testFloatObject(evalProgram($input), $expected);
    }
})->with([
    ['5', 5],
    ['10', 10],
    ['10 % 2', 0],
    ['11 % 2', 1],
    ['2 ** 2', 4],
    ['0.5 * 0.5', 0.25],
    ['9.5 + 0.5', 10.0],
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

test('eval string expressions', function (string $input, string $expected) {
    /** @var StringObject $object */
    $object = evalProgram($input);
    assertInstanceOf(StringObject::class, $object);
    assertSame(MonkeyObject::MO_STRING, $object->type());
    assertSame($expected, $object->value());
})->with([
    ['"foobar";', 'foobar'],
    ['"foo bar";', 'foo bar'],
    ['"foo" + " " + "baz";', 'foo baz'],
]);

test('eval boolean expression', function (string $input, bool $expected) {
    testBooleanObject(evalProgram($input), $expected);
})->with([
    ['true', true],
    ['false', false],
    ['1 > 1', false],
    ['1 < 1', false],
    ['"foo" == "foo"', true],
    ['"bar" == "foo"', false],
    ['"baz" != "foo"', true],
    ['true == false', false],
    ['true != false', true],
    ['true == true', true],
    ['true || true', true],
    ['true && true', true],
    ['true && false', false],
    ['false || true', true],
    ['false || false', false],
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

test('eval if else expressions', function (string $input, $expected) {
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
    ['if (true && true) { 10 }', 10],
    ['if (true || false) { 10 }', 10],
    ['if (1) { 10 }', 10],
    ['if (1 < 2) { 10 }', 10],
    ['if (1 > 2) { 10 } else { 20 }', 20],
    ['if (1 < 2) { 10 } else { 20 }', 10],
    ['if (5 * 5 + 10 > 34) { 99 } else { 100 }', 99],
]);

test('eval return statements', function (string $input, $expected) {
    testIntegerObject(evalProgram($input), $expected);
})->with([
    ['return 10;', 10],
    ['return 5;', 5],
    ['return 10; 9;', 10],
    ['return 2 * 5; 9;', 10],
    ['9; return 2 * 5; 9;', 10],
    ['if (10 > 1) { if (10 > 1) { return 10; } return 1; }', 10],
]);

test('error handling', function (string $input, string $expected) {
    /** @var ErrorObject $object */
    $object = evalProgram($input);
    assertInstanceOf(ErrorObject::class, $object);
    assertSame($expected, $object->value());
})->with([
    ['5 && true;', 'type mismatch: INTEGER && BOOL'],
    ['5 || true;', 'type mismatch: INTEGER || BOOL'],
    ['"1" || true;', 'type mismatch: STRING || BOOL'],
    ['5 + true;', 'type mismatch: INTEGER + BOOL'],
    ['5 + true; 5;', 'type mismatch: INTEGER + BOOL'],
    ['-true', 'unknown operator: -BOOL'],
    ['true + false', 'unknown operator: BOOL + BOOL'],
    ['5; true + false; 5', 'unknown operator: BOOL + BOOL'],
    ['if (10 > 1) { true + false; }', 'unknown operator: BOOL + BOOL'],
    ['if (10 > 1) { if (10 > 1) { return true + false; } return 1; }', 'unknown operator: BOOL + BOOL'],
    ['foobar', 'identifier not found: foobar'],
    ['"Hello" - "World"', 'unknown operator: STRING - STRING'],
]);

test('eval let statements', function (string $input, int $expected) {
    testIntegerObject(evalProgram($input), $expected);
})->with([
    ['let a = 5;', 5],
    ['let a = 5; a;', 5],
    ['let a = 5 * 5; a;', 25],
]);

test('eval assign statements', function (string $input, $expected) {
    if (\is_int($expected)) {
        testIntegerObject(evalProgram($input), $expected);
    } elseif (\is_float($expected)) {
        testFloatObject(evalProgram($input), $expected);
    } elseif (\is_string($expected)) {
        testStringObject(evalProgram($input), $expected);
    }
})->with([
    ['let a = 5; a = 10;', 10],
    ['let a = 5; a;', 5],
    ['let a = 5; a = 5.5;', 5.5],
    ['let a = 5 * 5; a = "foo";', 'foo'],
]);

test('function object', function () {
    /** @var FunctionObject $object */
    $object = evalProgram('fn(x) { x + 2; };');

    assertInstanceOf(FunctionObject::class, $object);
    assertCount(1, $object->parameters());

    assertSame('x', $object->parameters()[0]->toString());
    assertSame('(x + 2)', $object->body()->toString());
});

test('eval function', function (string $input, int $expected) {
    testIntegerObject(evalProgram($input), $expected);
})->with([
    ['let identity = fn(x) { x; }; identity(5);', 5],
    ['let identity = fn(x) { return x; }; identity(5);', 5],
    ['let double = fn(x) { x * 2; }; double(5);', 10],
    ['let add = fn(x, y) { x + y; }; add(5, 5);', 10],
    ['let add = fn(x, y) { x + y; }; add(5 + 5, add(5, 5));', 20],
    ['fn(x) { x; }(5)', 5],
]);

test('eval closure', function () {
    $input = <<<MONKEY
        let newAdder = fn(x) {
          fn(y) { 
            x + y 
          };
        };

        let addTwo = newAdder(2);
        addTwo(2);
    MONKEY;

    testIntegerObject(evalProgram($input), 4);
});

test('eval builtin len function', function (string $input, $expected) {
    $object = evalProgram($input);

    if ($object instanceof IntegerObject) {
        testIntegerObject($object, $expected);
    }

    assertSame($expected, $object->value());
})->with([
    ['len("")', 0],
    ['len("a")', 1],
    ['len("foo")', 3],
    ['len(1)', 'argument to "len" not supported, got INTEGER'],
    ['len("one", "two")', 'wrong number of arguments. got=2, want=1'],
]);

test('eval array literals', function (string $input, $expected) {
    /** @var ArrayObject $arrayObject */
    $arrayObject = evalProgram($input);

    /** @var MonkeyObject $element */
    foreach ($arrayObject->value() as $index => $element) {
        if ($element instanceof IntegerObject) {
            testIntegerObject($element, $expected[$index]);
        } else {
            assertSame($expected[$index], $element->value());
        }
    }
})->with([
    ['[1, 2 * 2, 3 + 3]', [1, 4, 6]],
    ['[1, 2, 3]', [1, 2, 3]],
    ['[1, "teste", 3]', [1, 'teste', 3]],
    ['[1, fn(x) { return x * 2; }(2), 8]', [1, 4, 8]],
]);

test('eval array index operations', function (string $input, ?int $expected) {
    /** @var IntegerObject $object */
    $object = evalProgram($input);

    if ($object instanceof NullObject) {
        assertNull($expected);
    }

    if ($object instanceof IntegerObject) {
        testIntegerObject($object, $expected);
    }
})->with([
    ['[1, 2, 3][0]', 1],
    ['[1, 2, 3][1]', 2],
    ['[1, 2, 3][2]', 3],
    ['let i = 0; [1][i];', 1],
    ['[1, 2, 3][1 + 1];', 3],
    ['let myArray = [1, 2, 3]; myArray[2];', 3],
    ['let myArray = [1, 2, 3]; myArray[0] + myArray[1] + myArray[2];', 6],
    ['let myArray = [1, 2, 3]; let i = myArray[0]; myArray[i]', 2],
    ['[1, 2, 3][3]', null],
    ['[1, 2, 3][-1]', null],
    ['[1, fn(x){x * 2}(2), "Baz"][fn(){1}()]', 4],
]);

test('eval while', function (string $input, int $expected) {
    testIntegerObject(evalProgram($input), $expected);
})->with([
    [
        <<<MONKEY
            let foo = fn() {
                let x = 0;
                while (x < 10) {
                    x = x + 1;
                }
                return x;
            };
            
            foo();
        MONKEY,
        10,
    ],
    [
        <<<MONKEY
        let foo = fn() {
            let x = 0;
            while (fn(x) { return x < 10; }(x)) {
                x = x + 1;
            }
            return x;
        };
        
        foo();
        MONKEY,
        10,
    ],
    [
        <<<MONKEY
        let x = 0;
        let foo = fn(x) {
            while (fn(x) { return x < 10; }(x)) {
                x = x + 1;
            }
            return x;
        };

        let y = foo(x);
        x;
        MONKEY,
        0,
    ],
]);
