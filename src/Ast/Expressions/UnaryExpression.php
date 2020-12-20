<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Token\Token;

final class UnaryExpression extends Expression
{
    public function __construct(
        Token $token,
        private string $operator,
        private Expression $right
    ) {
        $this->token = $token;
    }

    public function right(): Expression
    {
        return $this->right;
    }

    public function operator(): string
    {
        return $this->operator;
    }

    public function toString(): string
    {
        return "({$this->operator}{$this->right->toString()})";
    }
}
