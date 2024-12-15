<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Ast\Statements;

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Ast\Expressions\IdentifierExpression;
use MonkeyLang\Lang\Support\StringBuilder;
use MonkeyLang\Lang\Token\Token;

final class AssignStatement extends Statement
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
            ->append(' = ')
            ->append($this->value->toString())
            ->append(';')
            ->toString();
    }
}
