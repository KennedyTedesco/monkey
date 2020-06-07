<?php

declare(strict_types=1);

namespace Monkey\Ast;

use Monkey\Token\Token;

abstract class Node
{
    protected Token $token;

    public function tokenLiteral(): string
    {
        return $this->token->literal();
    }

    abstract public function toString(): string;
}
