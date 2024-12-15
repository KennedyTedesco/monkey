<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Ast\Types;

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Token\Token;

final class FloatLiteral extends Expression
{
    public function __construct(
        public readonly Token $token,
        public readonly float $value,
    ) {
    }

    public function toString(): string
    {
        return $this->token->literal;
    }
}