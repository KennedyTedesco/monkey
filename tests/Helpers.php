<?php

declare(strict_types=1);

use Monkey\Ast\Expressions\Expression;

/**
 * @param mixed $leftValue
 * @param $rightValue
 */
function assertInfixExpression(Expression $expression, $leftValue, string $operator, $rightValue): void
{
    assertSame($operator, $expression->operator());

    /** @var \Monkey\Ast\Types\Integer|\Monkey\Ast\Types\Boolean $right */
    $right = $expression->right();

    /** @var \Monkey\Ast\Types\Integer|\Monkey\Ast\Types\Boolean $left */
    $left = $expression->left();

    assertSame($leftValue, $left->value());
    assertSame($rightValue, $right->value());
}
