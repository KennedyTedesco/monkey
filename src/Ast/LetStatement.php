<?php

declare(strict_types=1);

namespace Monkey\Ast;

use Monkey\Token\Token;

final class LetStatement implements Statement
{
    private Token $token;
    private Identifier $identifier;
    private ?Expression $value = null;

    public function __construct(
        Token $token,
        Identifier $identifier
    ) {
        $this->token = $token;
        $this->identifier = $identifier;
    }

    public function tokenLiteral(): string
    {
        return $this->token->literal;
    }

    public function identifierName(): string
    {
        return $this->identifier->tokenLiteral();
    }

    public function toString(): string
    {
        return "{$this->tokenLiteral()} {$this->identifierName()} = {$this->identifier->value};";
    }
}
