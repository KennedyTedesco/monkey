<?php

declare(strict_types=1);

namespace Tests;

use Monkey\Ast\Expressions\BinaryExpression;
use Monkey\Ast\Expressions\CallExpression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Ast\Expressions\IfExpression;
use Monkey\Ast\Expressions\PrefixExpression;
use Monkey\Ast\Statements\BlockStatement;
use Monkey\Ast\Statements\ExpressionStatement;
use Monkey\Ast\Statements\LetStatement;
use Monkey\Ast\Statements\ReturnStatement;
use Monkey\Ast\Types\BooleanLiteral;
use Monkey\Ast\Types\FunctionLiteral;
use Monkey\Ast\Types\IntegerLiteral;

test('let statements', function (string $input, string $name, $value) {
    $program = newProgram($input);
    assertSame(1, $program->count());

    /** @var LetStatement $letStatement */
    $letStatement = $program->statement(0);
    assertInstanceOf(LetStatement::class, $letStatement);

    assertSame('let', $letStatement->tokenLiteral());
    assertSame($name, $letStatement->identifierName());

    /** @var IntegerLiteral|BooleanLiteral $valueExpression */
    $valueExpression = $letStatement->valueExpression();
    assertSame($value, $valueExpression->value());
})->with([
    ['let x = 5;', 'x', 5],
    ['let y = 10;', 'y', 10],
    ['let foo = true;', 'foo', true],
    ['let foo_bar = false;', 'foo_bar', false],
]);

test('return statement', function (string $input, $value) {
    $program = newProgram($input);
    assertSame(1, $program->count());

    /** @var ReturnStatement $returnStatement */
    $returnStatement = $program->statement(0);
    assertInstanceOf(ReturnStatement::class, $returnStatement);
    assertSame('return', $returnStatement->tokenLiteral());

    /** @var IntegerLiteral|BooleanLiteral $valueExpression */
    $valueExpression = $returnStatement->valueExpression();
    assertSame($value, $valueExpression->value());
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
    assertSame(1, $program->count());

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    assertInstanceOf(ExpressionStatement::class, $statement);

    /** @var IdentifierExpression $identifier */
    $identifier = $statement->value();

    assertSame('foobar', $identifier->value());
    assertSame('foobar', $identifier->tokenLiteral());
});

test('integer literal expression', function () {
    $input = '10;';

    $program = newProgram($input);
    assertSame(1, $program->count());

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);

    assertInstanceOf(ExpressionStatement::class, $statement);

    /** @var IntegerLiteral $integer */
    $integer = $statement->value();

    assertSame(10, $integer->value());
    assertSame('10', $integer->tokenLiteral());
});

test('prefix expression', function (string $input, string $operator, $value) {
    $program = newProgram($input);
    assertSame(1, $program->count());

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    assertInstanceOf(ExpressionStatement::class, $statement);

    /** @var PrefixExpression $expression */
    $expression = $statement->value();
    assertSame($operator, $expression->operator());

    /** @var IntegerLiteral|BooleanLiteral $right */
    $right = $expression->right();
    assertSame($value, $right->value());
})->with([
    ['!5;', '!', 5],
    ['-5;', '-', 5],
    ['!true;', '!', true],
    ['!false;', '!', false],
]);

test('infix expressions', function (string $input, $leftValue, string $operator, $rightValue) {
    $program = newProgram($input);
    assertSame(1, $program->count());

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    assertInstanceOf(ExpressionStatement::class, $statement);

    /** @var BinaryExpression $expression */
    $expression = $statement->value();

    assertInfixExpression($expression, $leftValue, $operator, $rightValue);
})->with([
    ['5 + 5;', 5, '+', 5],
    ['5 - 5;', 5, '-', 5],
    ['5 * 5;', 5, '*', 5],
    ['5 / 5;', 5, '/', 5],
    ['5 > 5;', 5, '>', 5],
    ['5 < 5;', 5, '<', 5],
    ['5 == 5;', 5, '==', 5],
    ['5 != 5;', 5, '!=', 5],
    ['true == true;', true, '==', true],
    ['false == false;', false, '==', false],
    ['true != false;', true, '!=', false],
]);

test('operator precedence parsing', function (string $input, string $expected) {
    $program = newProgram($input);
    assertSame($expected, $program->toString());
})->with([
    ['true', 'true'],
    ['false', 'false'],

    ['-a * b', '((-a) * b)'],
    ['!-a', '(!(-a))'],
    ['a + b + c', '((a + b) + c)'],
    ['a * b * c', '((a * b) * c)'],
    ['a * b / c', '((a * b) / c)'],
    ['a + b / c', '(a + (b / c))'],
    ['a + b * c + d / e - f', '(((a + (b * c)) + (d / e)) - f)'],

    ['3 + 4; -5 * 5', '(3 + 4)((-5) * 5)'],
    ['5 > 4 == 3 < 4', '((5 > 4) == (3 < 4))'],
    ['5 < 4 != 3 > 4', '((5 < 4) != (3 > 4))'],
    ['3 + 4 * 5 == 3 * 1 + 4 * 5', '((3 + (4 * 5)) == ((3 * 1) + (4 * 5)))'],

    ['1 + (2 + 3) + 4', '((1 + (2 + 3)) + 4)'],
    ['(5 + 5) * 2', '((5 + 5) * 2)'],
    ['2 / (5 + 5)', '(2 / (5 + 5))'],
    ['-(5 + 5)', '(-(5 + 5))'],
    ['!(true == true)', '(!(true == true))'],

    ['a + add(b * c) + d', '((a + add((b * c))) + d)'],
    ['add(a, b, 1, 2 * 3, 4 + 5, add(6, 7 * 8))', 'add(a, b, 1, (2 * 3), (4 + 5), add(6, (7 * 8)))'],
    ['add(a + b + c * d / f + g)', 'add((((a + b) + ((c * d) / f)) + g))'],
]);

test('if expression', function () {
    $program = newProgram('if (x < y) { x }');
    assertCount(1, $program->statements());

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    assertInstanceOf(ExpressionStatement::class, $statement);

    /** @var IfExpression $ifExpression */
    $ifExpression = $statement->value();
    assertInstanceOf(IfExpression::class, $ifExpression);

    // condition
    assertInfixExpression($ifExpression->condition(), 'x', '<', 'y');

    // consequence
    assertCount(1, $ifExpression->consequence()->statements());
    assertNull($ifExpression->alternative());

    /** @var ExpressionStatement $firstExpression */
    $firstExpression = $ifExpression->consequence()->statements()[0];
    assertInstanceOf(ExpressionStatement::class, $firstExpression);
    assertSame($firstExpression->tokenLiteral(), 'x');
});

test('if else expression', function () {
    $program = newProgram(<<<'MONKEY'
        if (x < y) {
            x
        } else {
            y
        }
    MONKEY);

    assertCount(1, $program->statements());

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    assertInstanceOf(ExpressionStatement::class, $statement);

    /** @var IfExpression $ifExpression */
    $ifExpression = $statement->value();
    assertInstanceOf(IfExpression::class, $ifExpression);

    // consequence
    assertCount(1, $ifExpression->consequence()->statements());

    /** @var BlockStatement $alternative */
    $alternative = $ifExpression->alternative();
    assertCount(1, $alternative->statements());
});

test('function literal', function () {
    $program = newProgram(<<<'MONKEY'
        fn(x, y) { 
            x + y; 
        }
    MONKEY);

    assertCount(1, $program->statements());

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    assertInstanceOf(ExpressionStatement::class, $statement);

    /** @var FunctionLiteral $functionLiteral */
    $functionLiteral = $statement->value();

    assertInstanceOf(FunctionLiteral::class, $functionLiteral);
    assertCount(2, $functionLiteral->parameters());
    assertSame('x', $functionLiteral->parameters()[0]->value());
    assertSame('y', $functionLiteral->parameters()[1]->value());
    assertCount(1, $functionLiteral->body()->statements());

    assertInfixExpression($functionLiteral->body()->statements()[0]->value(), 'x', '+', 'y');
});

test('function parameters', function (string $input, array $parameters) {
    $program = newProgram($input);
    assertCount(1, $program->statements());

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    assertInstanceOf(ExpressionStatement::class, $statement);

    /** @var FunctionLiteral $functionLiteral */
    $functionLiteral = $statement->value();
    assertInstanceOf(FunctionLiteral::class, $functionLiteral);

    $paramsTokenLiteral = \array_map(fn (IdentifierExpression $ident) => $ident->tokenLiteral(), $functionLiteral->parameters());
    assertSame($parameters, $paramsTokenLiteral);
})->with([
    ['fn() {};', []],
    ['fn(x) {};', ['x']],
    ['fn(x, y, z) {};', ['x', 'y', 'z']],
]);

test('call expression', function () {
    $program = newProgram('add(1, 2 * 3, 4 + 5);');
    assertCount(1, $program->statements());

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    assertInstanceOf(ExpressionStatement::class, $statement);

    /** @var CallExpression $callExpression */
    $callExpression = $statement->value();
    assertInstanceOf(CallExpression::class, $callExpression);

    assertSame('add', $callExpression->function()->tokenLiteral());
    assertCount(3, $callExpression->arguments());

    assertSame(1, $callExpression->arguments()[0]->value());
    assertInfixExpression($callExpression->arguments()[1], 2, '*', 3);
    assertInfixExpression($callExpression->arguments()[2], 4, '+', 5);
});
