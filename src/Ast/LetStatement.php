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

    public function identifierLiteral(): string
    {
        return $this->identifier->value;
    }

    public function statementNode()
    {
        // TODO: Implement statementNode() method.
    }
}
