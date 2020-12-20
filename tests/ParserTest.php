<?php

declare(strict_types=1);

namespace Tests;

use Monkey\Ast\Expressions\BinaryExpression;
use Monkey\Ast\Expressions\CallExpression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Ast\Expressions\IfExpression;
use Monkey\Ast\Expressions\IndexExpression;
use Monkey\Ast\Expressions\PostfixExpression;
use Monkey\Ast\Expressions\UnaryExpression;
use Monkey\Ast\Expressions\WhileExpression;
use Monkey\Ast\Statements\AssignStatement;
use Monkey\Ast\Statements\BlockStatement;
use Monkey\Ast\Statements\ExpressionStatement;
use Monkey\Ast\Statements\LetStatement;
use Monkey\Ast\Statements\ReturnStatement;
use Monkey\Ast\Types\ArrayLiteral;
use Monkey\Ast\Types\BooleanLiteral;
use Monkey\Ast\Types\FunctionLiteral;
use Monkey\Ast\Types\IntegerLiteral;
use Monkey\Ast\Types\StringLiteral;

test('let statements', function (string $input, string $name, $value) {
    $program = newProgram($input);
    expect($program->count())->toBe(1);

    /** @var LetStatement $letStatement */
    $letStatement = $program->statement(0);
    expect($letStatement)->toBeInstanceOf(LetStatement::class);

    expect($letStatement->tokenLiteral())->toBe('let');
    expect($letStatement->name()->tokenLiteral())->toBe($name);

    /** @var IntegerLiteral|BooleanLiteral $valueExpression */
    $valueExpression = $letStatement->value();
    expect($valueExpression->value())->toBe($value);
})->with([
    ['let x = 5;', 'x', 5],
    ['let y = 10;', 'y', 10],
    ['let foo = true;', 'foo', true],
    ['let foo_bar = false;', 'foo_bar', false],
]);

test('assign statements', function (string $input, string $name, $value) {
    $program = newProgram($input);
    expect($program->count())->toBe(1);

    /** @var AssignStatement $assignStatement */
    $assignStatement = $program->statement(0);
    expect($assignStatement)->toBeInstanceOf(AssignStatement::class);
    expect($assignStatement->name()->tokenLiteral())->toBe($name);

    /** @var IntegerLiteral|BooleanLiteral $valueExpression */
    $valueExpression = $assignStatement->value();
    expect($valueExpression->value())->toBe($value);
})->with([
    ['x = 5;', 'x', 5],
    ['y = 10;', 'y', 10],
    ['foo = true;', 'foo', true],
]);

test('return statement', function (string $input, $value) {
    $program = newProgram($input);
    expect($program->count())->toBe(1);

    /** @var ReturnStatement $returnStatement */
    $returnStatement = $program->statement(0);
    expect($returnStatement)->toBeInstanceOf(ReturnStatement::class);
    expect($returnStatement->tokenLiteral())->toBe('return');

    /** @var IntegerLiteral|BooleanLiteral $valueExpression */
    $valueExpression = $returnStatement->returnValue();
    expect($valueExpression->value())->toBe($value);
})->with([
    ['return 10;', 10],
    ['return 100;', 100],
    ['return 1000;', 1000],
    ['return true;', true],
    ['return false;', false],
]);

test('identifier expression', function () {
    $input = 'foobar;';

    $program = newProgram($input);
    expect($program->count())->toBe(1);

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    expect($statement)->toBeInstanceOf(ExpressionStatement::class);

    /** @var IdentifierExpression $identifier */
    $identifier = $statement->expression();

    expect($identifier->value())->toBe('foobar');
    expect($identifier->tokenLiteral())->toBe('foobar');
});

test('integer literal expression', function () {
    $input = '10;';

    $program = newProgram($input);
    expect($program->count())->toBe(1);

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    expect($statement)->toBeInstanceOf(ExpressionStatement::class);

    /** @var IntegerLiteral $integer */
    $integer = $statement->expression();

    expect($integer->value())->toBe(10);
    expect($integer->tokenLiteral())->toBe('10');
});

test('string literal expression', function () {
    $input = '"foobar";';

    $program = newProgram($input);
    expect($program->count())->toBe(1);

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    expect($statement)->toBeInstanceOf(ExpressionStatement::class);

    /** @var StringLiteral $string */
    $string = $statement->expression();
    expect($string->value())->toBe('foobar');
});

test('array literal expression', function () {
    $input = '[1, 2 * 2, 3 + 3]';

    $program = newProgram($input);
    expect($program->count())->toBe(1);

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    expect($statement)->toBeInstanceOf(ExpressionStatement::class);

    /** @var ArrayLiteral $array */
    $array = $statement->expression();
    expect($array->elements())->toHaveCount(3);

    /** @var IntegerLiteral $firstElement */
    $firstElement = $array->elements()[0];
    expect($firstElement)->toBeInstanceOf(IntegerLiteral::class);
    expect($firstElement->value())->toBe(1);

    assertInfixExpression($array->elements()[1], 2, '*', 2);
    assertInfixExpression($array->elements()[2], 3, '+', 3);
});

test('array index expression', function () {
    $input = 'foo[1 + 2]';

    $program = newProgram($input);
    expect($program->count())->toBe(1);

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    expect($statement)->toBeInstanceOf(ExpressionStatement::class);

    /** @var IndexExpression $indexExpression */
    $indexExpression = $statement->expression();
    expect($indexExpression)->toBeInstanceOf(IndexExpression::class);

    /** @var IdentifierExpression $identifier */
    $identifier = $indexExpression->left();
    expect($identifier->value())->toBe('foo');

    assertInfixExpression($indexExpression->index(), 1, '+', 2);
});

test('prefix expression', function (string $input, string $operator, $value) {
    $program = newProgram($input);
    expect($program->count())->toBe(1);

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    expect($statement)->toBeInstanceOf(ExpressionStatement::class);

    /** @var UnaryExpression $expression */
    $expression = $statement->expression();
    expect($expression->operator())->toBe($operator);

    /** @var IntegerLiteral|BooleanLiteral $right */
    $right = $expression->right();
    expect($right->value())->toBe($value);
})->with([
    ['!5;', '!', 5],
    ['-5;', '-', 5],
    ['!true;', '!', true],
    ['!false;', '!', false],
]);

test('infix expressions', function (string $input, $leftValue, string $operator, $rightValue) {
    $program = newProgram($input);
    expect($program->count())->toBe(1);

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    expect($statement)->toBeInstanceOf(ExpressionStatement::class);

    /** @var BinaryExpression $expression */
    $expression = $statement->expression();

    assertInfixExpression($expression, $leftValue, $operator, $rightValue);
})->with([
    ['10.5 + 0.5;', 10.5, '+', 0.5],
    ['5 + 5;', 5, '+', 5],
    ['5 - 5;', 5, '-', 5],
    ['5 * 5;', 5, '*', 5],
    ['5 / 5;', 5, '/', 5],
    ['5 > 5;', 5, '>', 5],
    ['5 < 5;', 5, '<', 5],
    ['10 % 2;', 10, '%', 2],
    ['2 ** 2;', 2, '**', 2],
    ['5 == 5;', 5, '==', 5],
    ['5 != 5;', 5, '!=', 5],
    ['true == true;', true, '==', true],
    ['false == false;', false, '==', false],
    ['true != false;', true, '!=', false],
    ['true && false;', true, '&&', false],
    ['false || false;', false, '||', false],
]);

test('postfix expressions', function (string $input, string $operator) {
    $program = newProgram($input);
    expect($program->count())->toBe(2);

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(1);
    expect($statement)->toBeInstanceOf(ExpressionStatement::class);

    /** @var PostfixExpression $expression */
    $expression = $statement->expression();
    expect($expression)->toBeInstanceOf(PostfixExpression::class);
    expect($expression->operator())->toBe($operator);
})->with([
    ['a++;', '++'],
    ['1--;', '--'],
    ['a++;', '++'],
    ['b--;', '--'],
    ['b--;', '--'],
]);

test('operator precedence parsing', function (string $input, string $expected) {
    $program = newProgram($input);
    expect($program->toString())->toBe($expected);
})->with([
    ['true', 'true'],
    ['false', 'false'],

    ['-a * b', '((-a) * b)'],
    ['!-a', '(!(-a))'],
    ['a + b + c', '((a + b) + c)'],
    ['a * b * c', '((a * b) * c)'],
    ['a * b / c', '((a * b) / c)'],
    ['a + b / c', '(a + (b / c))'],
    ['a % b * c', '((a % b) * c)'],
    ['a % b * c', '((a % b) * c)'],
    ['a ** b * c * d', '(((a ** b) * c) * d)'],
    ['a + b * c + d / e - f', '(((a + (b * c)) + (d / e)) - f)'],

    ['3 + 4; -5 * 5', '(3 + 4)((-5) * 5)'],
    ['5 > 4 == 3 < 4', '((5 > 4) == (3 < 4))'],
    ['5 < 4 != 3 > 4', '((5 < 4) != (3 > 4))'],
    ['3 + 4 * 5 == 3 * 1 + 4 * 5', '((3 + (4 * 5)) == ((3 * 1) + (4 * 5)))'],

    ['1 + (2 + 3) + 4', '((1 + (2 + 3)) + 4)'],
    ['(5 + 5) * 2', '((5 + 5) * 2)'],
    ['2 / (5 + 5)', '(2 / (5 + 5))'],
    ['-(5 + 5)', '(-(5 + 5))'],
    ['-(0.5 + 0.1)', '(-(0.5 + 0.1))'],
    ['!(true == true)', '(!(true == true))'],
    ['true && false || true', '((true && false) || true)'],
    ['true || false || true', '((true || false) || true)'],
    ['false || true && false', '(false || (true && false))'],

    ['a + add(b * c) + d', '((a + add((b * c))) + d)'],
    ['add(a, b, 1, 2 * 3, 4 + 5, add(6, 7 * 8))', 'add(a, b, 1, (2 * 3), (4 + 5), add(6, (7 * 8)))'],
    ['add(a + b + c * d / f + g)', 'add((((a + b) + ((c * d) / f)) + g))'],

    ['a * [1, 2, 3, 4][b * c] * d', '((a * ([1,2,3,4][(b * c)])) * d)'],
    ['add(a * b[2], b[1], 2 * [1, 2][1])', 'add((a * (b[2])), (b[1]), (2 * ([1,2][1])))'],
]);

test('if expression', function () {
    $program = newProgram('if (x < 10) { x }');
    expect($program->statements())->toHaveCount(1);

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    expect($statement)->toBeInstanceOf(ExpressionStatement::class);

    /** @var IfExpression $ifExpression */
    $ifExpression = $statement->expression();
    expect($ifExpression)->toBeInstanceOf(IfExpression::class);

    // condition
    assertInfixExpression($ifExpression->condition(), 'x', '<', 10);

    // consequence
    expect($ifExpression->consequence()->statements())->toHaveCount(1);
    expect($ifExpression->alternative())->toBeNull();

    /** @var ExpressionStatement $firstExpression */
    $firstExpression = $ifExpression->consequence()->statements()[0];
    expect($firstExpression)->toBeInstanceOf(ExpressionStatement::class);
    expect($firstExpression->tokenLiteral())->toBe('x');
});

test('while expression', function () {
    $program = newProgram(<<<MONKEY
        while (x < 10) {
            x
        }
    MONKEY);

    expect($program->statements())->toHaveCount(1);

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    expect($statement)->toBeInstanceOf(ExpressionStatement::class);

    /** @var WhileExpression $whileExpression */
    $whileExpression = $statement->expression();
    expect($whileExpression)->toBeInstanceOf(WhileExpression::class);

    // condition
    assertInfixExpression($whileExpression->condition(), 'x', '<', 10);

    // consequence
    expect($whileExpression->consequence()->statements())->toHaveCount(1);

    /** @var ExpressionStatement $firstExpression */
    $firstExpression = $whileExpression->consequence()->statements()[0];
    expect($firstExpression)->toBeInstanceOf(ExpressionStatement::class);
    expect($firstExpression->tokenLiteral())->toBe('x');
});

test('if else expression', function () {
    $program = newProgram(<<<'MONKEY'
        if (x < y) {
            x
        } else {
            y
        }
    MONKEY);

    expect($program->statements())->toHaveCount(1);

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    expect($statement)->toBeInstanceOf(ExpressionStatement::class);

    /** @var IfExpression $ifExpression */
    $ifExpression = $statement->expression();
    expect($ifExpression)->toBeInstanceOf(IfExpression::class);

    // consequence
    expect($ifExpression->consequence()->statements())->toHaveCount(1);

    /** @var BlockStatement $alternative */
    $alternative = $ifExpression->alternative();
    expect($alternative->statements())->toHaveCount(1);
});

test('function literal', function () {
    $program = newProgram(<<<'MONKEY'
        fn(x, y) {
            x + y;
        }
    MONKEY);

    expect($program->statements())->toHaveCount(1);

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    expect($statement)->toBeInstanceOf(ExpressionStatement::class);

    /** @var FunctionLiteral $functionLiteral */
    $functionLiteral = $statement->expression();

    expect($functionLiteral)->toBeInstanceOf(FunctionLiteral::class);
    expect($functionLiteral->parameters())->toHaveCount(2);
    expect($functionLiteral->parameters()[0]->value())->toBe('x');
    expect($functionLiteral->parameters()[1]->value())->toBe('y');
    expect($functionLiteral->body()->statements())->toHaveCount(1);

    assertInfixExpression($functionLiteral->body()->statements()[0]->expression(), 'x', '+', 'y');
});

test('function parameters', function (string $input, array $parameters) {
    $program = newProgram($input);
    expect($program->statements())->toHaveCount(1);

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    expect($statement)->toBeInstanceOf(ExpressionStatement::class);

    /** @var FunctionLiteral $functionLiteral */
    $functionLiteral = $statement->expression();
    expect($functionLiteral)->toBeInstanceOf(FunctionLiteral::class);

    $paramsTokenLiteral = \array_map(fn (IdentifierExpression $ident) => $ident->tokenLiteral(), $functionLiteral->parameters());
    expect($paramsTokenLiteral)->toBe($parameters);
})->with([
    ['fn() {};', []],
    ['fn(x) {};', ['x']],
    ['fn(x, y, z) {};', ['x', 'y', 'z']],
]);

test('call expression', function () {
    $program = newProgram('add(1, 2 * 3, 4 + 5);');
    expect($program->statements())->toHaveCount(1);

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    expect($statement)->toBeInstanceOf(ExpressionStatement::class);

    /** @var CallExpression $callExpression */
    $callExpression = $statement->expression();
    expect($callExpression)->toBeInstanceOf(CallExpression::class);

    expect($callExpression->function()->tokenLiteral())->toBe('add');
    expect($callExpression->arguments())->toHaveCount(3);

    expect($callExpression->arguments()[0]->value())->toBe(1);
    assertInfixExpression($callExpression->arguments()[1], 2, '*', 3);
    assertInfixExpression($callExpression->arguments()[2], 4, '+', 5);
});

test('program to string', function () {
    $program = newProgram('let x = 1 * 2 * 3 * 4 * 5;');
    expect($program->toString())->toBe('let x = ((((1 * 2) * 3) * 4) * 5);');
});

test('program statements', function () {
    $program = newProgram('5');
    expect($program->statements())->toHaveCount(1);
    expect($program->statement(0))->toBeInstanceOf(ExpressionStatement::class);

    /** @var ExpressionStatement $expression */
    $expression = $program->statement(0);

    /** @var IntegerLiteral $integerLiteral */
    $integerLiteral = $expression->expression();
    expect($integerLiteral)->toBeInstanceOf(IntegerLiteral::class);
    expect($integerLiteral->value())->toBe(5);
});
