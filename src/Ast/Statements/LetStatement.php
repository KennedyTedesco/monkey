<?php

declare(strict_types=1);

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Token\Token;

final class LetStatement extends Statement
{
    private Expression $valueExpression;
    private IdentifierExpression $identifier;

    public function __construct(
        Token $token,
        IdentifierExpression $identifier,
        Expression $valueExpression
    ) {
        $this->token = $token;
        $this->identifier = $identifier;
        $this->valueExpression = $valueExpression;
    }

    public function valueExpression(): Expression
    {
        return $this->valueExpression;
    }

    public function identifierName(): string
    {
        return $this->identifier->tokenLiteral();
    }

    public function toString(): string
    {
        return "{$this->tokenLiteral()} {$this->identifierName()} = {$this->identifier->value()};";
    }
}
