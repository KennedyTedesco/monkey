<?php

declare(strict_types=1);

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Types\BooleanLiteral;
use Monkey\Ast\Types\IntegerLiteral;

/**
 * @param mixed $leftValue
 * @param $rightValue
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
