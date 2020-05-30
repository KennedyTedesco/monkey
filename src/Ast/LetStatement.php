<?php

declare(strict_types=1);

namespace Monkey\Ast;

use Monkey\Token\Token;

final class LetStatement implements Statement
{
    private Token $token;
    private Expression $value;
    private Identifier $identifier;

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
        return $this->identifier->value;
    }

    public function statementNode()
    {
        // TODO: Implement statementNode() method.
    }

    public function toString(): string
    {
        return "{$this->tokenLiteral()} {$this->identifierName()} = {$this->value->toString()};";
    }
}
