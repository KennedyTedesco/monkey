<?php

declare(strict_types=1);

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\Identifier;
use Monkey\Token\Token;

final class LetStatement extends Statement
{
    private Identifier $identifier;
    private ?Expression $value = null;

    public function __construct(
        Token $token,
        Identifier $identifier
    ) {
        $this->token = $token;
        $this->identifier = $identifier;
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
