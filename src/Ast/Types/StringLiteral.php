<?php

declare(strict_types=1);

namespace MonkeyLang\Ast\Types;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Token\Token;

final class StringLiteral extends Expression
{
    public function __construct(
        public readonly Token $token,
        public readonly string $value,
    ) {
    }

    public function toString(): string
    {
        return $this->token->literal;
    }
}
