<?php

declare(strict_types=1);

namespace MonkeyLang\Ast\Expressions;

use MonkeyLang\Token\Token;

final class IdentifierExpression extends Expression
{
    public function __construct(
        public readonly Token $token,
        public readonly string $value,
    ) {
    }

    public function toString(): string
    {
        return $this->value;
    }
}
