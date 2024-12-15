<?php

declare(strict_types=1);

namespace MonkeyLang\Ast\Statements;

namespace MonkeyLang\Lang\Ast\Statements;

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Support\StringBuilder;
use MonkeyLang\Lang\Token\Token;

final class ReturnStatement extends Statement
{
    public function __construct(
        public readonly Token $token,
        public readonly Expression $value,
    ) {
    }

    public function toString(): string
    {
        return StringBuilder::new()
            ->append($this->token->literal)
            ->appendSpace()
            ->append($this->value)
            ->append(';')
            ->toString();
    }
}
