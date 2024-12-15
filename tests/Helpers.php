<?php

declare(strict_types=1);

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Ast\Program;
use MonkeyLang\Ast\Types\BooleanLiteral;
use MonkeyLang\Ast\Types\IntegerLiteral;
use MonkeyLang\Evaluator\Environment;
use MonkeyLang\Evaluator\Evaluator;
use MonkeyLang\Lexer\Lexer;
use MonkeyLang\Object\MonkeyObject;
use MonkeyLang\Parser\Parser;
use MonkeyLang\Parser\ProgramParser;

function assertInfixExpression(Expression $expression, $leftValue, string $operator, $rightValue): void
{
    expect($expression->operator)->toBe($operator);

    /** @var BooleanLiteral|IntegerLiteral $right */
    $right = $expression->right;

    /** @var BooleanLiteral|IntegerLiteral $left */
    $left = $expression->left;

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
