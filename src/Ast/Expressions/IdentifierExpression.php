<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Token\Token;

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
