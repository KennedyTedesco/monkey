<?php

declare(strict_types=1);

namespace Tests;

use Monkey\Ast\Expressions\Identifier;
use Monkey\Ast\Expressions\PrefixExpression;
use Monkey\Ast\Statements\ExpressionStatement;
use Monkey\Ast\Statements\LetStatement;
use Monkey\Ast\Statements\ReturnStatement;
use Monkey\Ast\Types\IntegerLiteral;
use Monkey\Lexer\Lexer;
use Monkey\Parser\Parser;
use Monkey\Parser\ProgramParser;

test('let parser', function () {
    $input = <<<'MONKEY'
        let x = 5;
        let y = 10;
        let foo_bar = 838383;
MONKEY;

    $lexer = new Lexer($input);
    $parser = new Parser($lexer);
    $program = (new ProgramParser())($parser);

    assertSame(3, $program->count());
    assertCount(0, $parser->errors());

    $identifiers = ['x', 'y', 'foo_bar'];

    /** @var LetStatement $stmt */
    foreach ($program->statements() as $index => $stmt) {
        assertInstanceOf(LetStatement::class, $stmt);
        assertSame('let', $stmt->tokenLiteral());
        assertSame($identifiers[$index], $stmt->identifierName());
    }
});

test('return parser', function (string $input) {
    $lexer = new Lexer($input);
    $parser = new Parser($lexer);
    $program = (new ProgramParser())($parser);

    assertSame(1, $program->count());
    assertCount(0, $parser->errors());

    /** @var ReturnStatement $returnStatement */
    $returnStatement = $program->statement(0);
    assertInstanceOf(ReturnStatement::class, $returnStatement);
    assertSame('return', $returnStatement->tokenLiteral());
})->with([
    'return 10;',
    'return 100;',
    'return 1000;',
]);

test('identifier expression', function () {
    $input = 'foobar;';

    $lexer = new Lexer($input);
    $parser = new Parser($lexer);
    $program = (new ProgramParser())($parser);

    assertSame(1, $program->count());

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    assertInstanceOf(ExpressionStatement::class, $statement);

    /** @var Identifier $identifier */
    $identifier = $statement->value();

    assertSame('foobar', $identifier->value());
    assertSame('foobar', $identifier->tokenLiteral());
});

test('integer literal expression', function () {
    $input = '10;';

    $lexer = new Lexer($input);
    $parser = new Parser($lexer);
    $program = (new ProgramParser())($parser);

    assertSame(1, $program->count());

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);

    assertInstanceOf(ExpressionStatement::class, $statement);

    /** @var IntegerLiteral $integer */
    $integer = $statement->value();

    assertSame(10, $integer->value());
    assertSame('10', $integer->tokenLiteral());
});

test('prefix expression', function (string $input, string $operator, int $value) {
    $lexer = new Lexer($input);
    $parser = new Parser($lexer);
    $program = (new ProgramParser())($parser);

    assertSame(1, $program->count());

    /** @var ExpressionStatement $statement */
    $statement = $program->statement(0);
    assertInstanceOf(ExpressionStatement::class, $statement);

    /** @var PrefixExpression $expression */
    $expression = $statement->value();
    assertSame($operator, $expression->operator());

    /** @var IntegerLiteral $right */
    $right = $expression->right();
    assertSame($value, $right->value());
})->with([
    ['!5;', '!', 5],
    ['-5;', '-', 5],
]);

test('operator precedence parsing', function (string $input, string $expected) {
    $lexer = new Lexer($input);
    $program = (new ProgramParser())(
        new Parser($lexer)
    );

    assertSame($expected, $program->toString());
})->with([
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
]);
