<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Token\Token;

final class PostfixExpression extends Expression
{
    public function __construct(
        Token $token,
        public readonly string $operator,
    ) {
        $this->token = $token;
    }

    public function toString(): string
    {
        return "({$this->tokenLiteral()}{$this->operator})";
    }
}
