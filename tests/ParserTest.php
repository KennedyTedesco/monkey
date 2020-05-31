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
    foreach ($program->statements() as $i => $stmt) {
        assertInstanceOf(LetStatement::class, $stmt);
        assertSame('let', $stmt->tokenLiteral());
        assertSame($identifiers[$i], $stmt->identifierName());
    }
});

test('return parser', function () {
    $input = <<<'MONKEY'
    return 10;
    return 100;
    return 1000;
MONKEY;

    $lexer = new Lexer($input);
    $parser = new Parser($lexer);
    $program = (new ProgramParser())($parser);

    assertSame(3, $program->count());
    assertCount(0, $parser->errors());

    /** @var LetStatement $stmt */
    foreach ($program->statements() as $stmt) {
        assertInstanceOf(ReturnStatement::class, $stmt);
        assertSame('return', $stmt->tokenLiteral());
    }
});

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
