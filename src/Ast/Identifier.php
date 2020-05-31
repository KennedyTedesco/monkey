<?php

declare(strict_types=1);

namespace Monkey\Ast;

use Monkey\Token\Token;

final class Identifier implements Expression
{
    private Token $token;
    public string $value;

    public function __construct(Token $token, string $value)
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
        return '';
    }
}
