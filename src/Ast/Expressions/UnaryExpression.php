<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Token\Token;

final class UnaryExpression extends Expression
{
    public function __construct(
        Token $token,
        public readonly string $operator,
        public readonly Expression $right,
    ) {
        $this->token = $token;
    }

    public function toString(): string
    {
        return "({$this->operator}{$this->right->toString()})";
    }
}
