<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Token\Token;

final class PrefixExpression extends Expression
{
    private Expression $right;
    private string $operator;

    public function __construct(
        Token $token,
        string $operator,
        Expression $right
    ) {
        $this->token = $token;
        $this->right = $right;
        $this->operator = $operator;
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
