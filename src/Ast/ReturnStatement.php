<?php

declare(strict_types=1);

namespace Monkey\Ast;

use Monkey\Token\Token;

final class ReturnStatement implements Statement
{
    private Token $token;
    private ?Expression $value;

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
        $out = "{$this->tokenLiteral()} ";
        if (null !== $this->value) {
            $out .= $this->value->toString();
        }
        $out .= ';';
        return $out;
    }
}
