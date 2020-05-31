<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Ast\Node;
use Monkey\Token\Token;

final class PrefixExpression extends Expression
{
    private Node $right;
    private string $operator;

    public function __construct(
        Token $token,
        Node $right,
        string $operator
    ) {
        $this->token = $token;
        $this->right = $right;
        $this->operator = $operator;
    }

    public function right(): Node
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
