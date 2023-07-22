<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Token\Token;

final class IdentifierExpression extends Expression
{
    public function __construct(
        Token $token,
        public readonly string $value,
    ) {
        $this->token = $token;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
