<?php

declare(strict_types=1);

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Ast\Program;
use MonkeyLang\Lang\Ast\Types\BooleanLiteral;
use MonkeyLang\Lang\Ast\Types\IntegerLiteral;
use MonkeyLang\Lang\Evaluator\Environment;
use MonkeyLang\Lang\Evaluator\Evaluator;
use MonkeyLang\Lang\Lexer\Lexer;
use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Parser\Parser;
use MonkeyLang\Lang\Parser\ProgramParser;

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
