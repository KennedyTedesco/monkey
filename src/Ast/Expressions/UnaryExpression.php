<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Support\StringBuilder;
use Monkey\Token\Token;

final class UnaryExpression extends Expression
{
    public function __construct(
        public readonly Token $token,
        public readonly string $operator,
        public readonly Expression $right,
    ) {
    }

    public function toString(): string
    {
        return StringBuilder::new()
            ->append('(')
            ->append($this->operator)
            ->append($this->right)
            ->append(')')
            ->toString();
    }
}
