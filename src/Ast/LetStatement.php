<?php

declare(strict_types=1);

namespace Monkey\Ast;

use Monkey\Token\Token;

final class LetStatement implements Statement
{
    private Token $token;
    private ?Identifier $name;
    private Expression $value;

    public function __construct(
        Token $token,
        Identifier $name = null
    ) {
        $this->token = $token;
        $this->name = $name;
    }

    public function tokenLiteral(): string
    {
        return $this->token->literal();
    }

    public function statementNode()
    {
        // TODO: Implement statementNode() method.
    }
}
