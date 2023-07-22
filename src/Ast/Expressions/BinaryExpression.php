<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Token\Token;

final class BinaryExpression extends Expression
{
    public function __construct(
        Token $token,
        public readonly string $operator,
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
        $this->token = $token;
    }

    public function toString(): string
    {
        return "({$this->left->toString()} {$this->operator} {$this->right->toString()})";
    }
}
