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

/**
 * @param mixed $leftValue
 * @param mixed $rightValue
 */
function assertInfixExpression(Expression $expression, $leftValue, string $operator, $rightValue): void
{
    assertSame($operator, $expression->operator());

    /** @var IntegerLiteral|BooleanLiteral $right */
    $right = $expression->right();

    /** @var IntegerLiteral|BooleanLiteral $left */
    $left = $expression->left();

    assertSame($leftValue, $left->value());
    assertSame($rightValue, $right->value());
}

function newProgram(string $input): Program
{
    return (new ProgramParser())(
        new Parser(
            new Lexer($input)
        )
    );
}

function evalProgram(string $input): MonkeyObject
{
    return (new Evaluator())->eval(newProgram($input), Environment::new());
}
