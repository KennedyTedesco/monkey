<?php

declare(strict_types=1);

namespace MonkeyLang\Ast\Types;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Token\Token;

final class BooleanLiteral extends Expression
{
    public function __construct(
        public readonly Token $token,
        public readonly bool $value,
    ) {
    }

    public function toString(): string
    {
        return $this->token->literal;
    }
}
