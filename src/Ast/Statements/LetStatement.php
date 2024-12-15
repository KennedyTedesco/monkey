<?php

declare(strict_types=1);

namespace MonkeyLang\Ast\Statements;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Ast\Expressions\IdentifierExpression;
use MonkeyLang\Support\StringBuilder;
use MonkeyLang\Token\Token;

final class LetStatement extends Statement
{
    public function __construct(
        public readonly Token $token,
        public readonly IdentifierExpression $name,
        public readonly Expression $value,
    ) {
    }

    public function toString(): string
    {
        return StringBuilder::new()
            ->append($this->token->literal)
            ->appendSpace()
            ->append($this->name->token->literal)
            ->append(' = ')
            ->append($this->value)
            ->append(';')
            ->toString();
    }
}
