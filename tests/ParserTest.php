<?php

declare(strict_types=1);

namespace Tests;

use Monkey\Ast\Expressions\BinaryExpression;
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

test('let parser', function () {
    $input = <<<'MONKEY'
        let x = 5;
        let y = 10;
        let foo_bar = 838383;
MONKEY;

    $program = newProgram($input);
    assertSame(3, $program->count());

    $identifiers = ['x', 'y', 'foo_bar'];

    /** @var LetStatement $stmt */
    foreach ($program->statements() as $index => $stmt) {
        assertInstanceOf(LetStatement::class, $stmt);
        assertSame('let', $stmt->tokenLiteral());
        assertSame($identifiers[$index], $stmt->identifierName());
    }
});

test('return parser', function (string $input) {
    $program = newProgram($input);
    assertSame(1, $program->count());

    /** @var ReturnStatement $returnStatement */
    $returnStatement = $program->statement(0);
    assertInstanceOf(ReturnStatement::class, $returnStatement);
    assertSame('return', $returnStatement->tokenLiteral());
})->with([
    'return 10;',
    'return 100;',
    'return 1000;',
    'return true;',
    'return false;',
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
    $input = <<<MONKEY
    if (x < y) {
        x
    } else {
        y
    }
MONKEY;

    $program = newProgram($input);
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
    $program = newProgram('fn(x, y) { x + y; }');
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
