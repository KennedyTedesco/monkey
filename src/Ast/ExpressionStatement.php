<?php

declare(strict_types=1);

namespace Monkey\Ast;

use Monkey\Token\Token;

final class ExpressionStatement implements Statement
{
    private Token $token;
    public ?Expression $value;

    public function __construct(Token $token, ?Expression $value = null)
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
