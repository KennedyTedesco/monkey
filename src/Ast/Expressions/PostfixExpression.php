<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Support\StringBuilder;
use Monkey\Token\Token;

final class PostfixExpression extends Expression
{
    public function __construct(
        Token $token,
        public readonly string $operator,
    ) {
        $this->token = $token;
    }

    public function toString(): string
    {
        return StringBuilder::new()
            ->append('(')
            ->append($this->tokenLiteral())
            ->append($this->operator)
            ->append(')')
            ->toString();
    }
}
