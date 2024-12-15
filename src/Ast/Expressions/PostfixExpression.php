<?php

declare(strict_types=1);

namespace MonkeyLang\Ast\Expressions;

use MonkeyLang\Support\StringBuilder;
use MonkeyLang\Token\Token;

final class PostfixExpression extends Expression
{
    public function __construct(
        public readonly Token $token,
        public readonly string $operator,
    ) {
    }

    public function toString(): string
    {
        return StringBuilder::new()
            ->append('(')
            ->append($this->token->literal)
            ->append($this->operator)
            ->append(')')
            ->toString();
    }
}
