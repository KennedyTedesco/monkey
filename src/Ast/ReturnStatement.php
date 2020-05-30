<?php

declare(strict_types=1);

namespace Monkey\Ast;

use Monkey\Token\Token;

final class ReturnStatement implements Statement
{
    private Token $token;
    private Expression $value;

    public function __construct(Token $token)
    {
        $this->token = $token;
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
