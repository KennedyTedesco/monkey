<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Ast\Expressions;

use MonkeyLang\Lang\Support\StringBuilder;
use MonkeyLang\Lang\Token\Token;

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
