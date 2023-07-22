<?php

declare(strict_types=1);

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Program;
use Monkey\Ast\Types\BooleanLiteral;
use Monkey\Ast\Types\IntegerLiteral;
use Monkey\Evaluator\Environment;
use Monkey\Evaluator\Evaluator;
use Monkey\Lexer\Lexer;
use Monkey\Object\MonkeyObject;
use Monkey\Parser\Parser;
use Monkey\Parser\ProgramParser;

function assertInfixExpression(Expression $expression, $leftValue, string $operator, $rightValue): void
{
    expect($expression->operator())->toBe($operator);

    /** @var BooleanLiteral|IntegerLiteral $right */
    $right = $expression->right();

    /** @var BooleanLiteral|IntegerLiteral $left */
    $left = $expression->left();

    expect($leftValue)->toBe($left->value);
    expect($rightValue)->toBe($right->value);
}

function newProgram(string $input): Program
{
    return (new ProgramParser())(
        new Parser(
            new Lexer($input),
        )
    );
}

function evalProgram(string $input): MonkeyObject
{
    return (new Evaluator())->eval(newProgram($input), new Environment());
}
