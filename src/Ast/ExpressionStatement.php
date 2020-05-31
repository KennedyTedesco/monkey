<?php

declare(strict_types=1);

namespace Monkey\Ast;

use Monkey\Token\Token;

final class ExpressionStatement implements Statement
{
    private Token $token;
    private Expression $value;

    public function __construct(Token $token, Expression $value)
    {
        $this->token = $token;
        $this->value = $value;
    }

    public function tokenLiteral(): string
    {
        return $this->token->literal;
    }

    public function toString(): string
    {
        return $this->value->toString();
    }
}
