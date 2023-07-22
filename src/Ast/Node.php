<?php

declare(strict_types=1);

namespace Monkey\Ast;

use Monkey\Token\Token;
use Stringable;

abstract class Node implements Stringable
{
    protected Token $token;

    public function tokenLiteral(): string
    {
        return $this->token->literal();
    }

    abstract public function toString(): string;

    public function __toString(): string
    {
        return $this->toString();
    }
}
