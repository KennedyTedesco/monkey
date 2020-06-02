<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Token\Token;

final class InfixExpression extends Expression
{
    private string $operator;
    private Expression $left;
    private Expression $right;

    public function __construct(
        Token $token,
        string $operator,
        Expression $left,
        Expression $right
    ) {
        $this->token = $token;
        $this->right = $right;
        $this->left = $left;
        $this->operator = $operator;
    }

    public function left(): Expression
    {
        return $this->left;
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
        return "({$this->left->toString()} {$this->operator} {$this->right->toString()})";
    }
}
