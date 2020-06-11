<?php

declare(strict_types=1);

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Token\Token;

final class LetStatement extends Statement
{
    private Expression $valueExpression;
    private IdentifierExpression $name;

    public function __construct(
        Token $token,
        IdentifierExpression $name,
        Expression $valueExpression
    ) {
        $this->token = $token;
        $this->name = $name;
        $this->valueExpression = $valueExpression;
    }

    public function name(): IdentifierExpression
    {
        return $this->name;
    }

    public function valueExpression(): Expression
    {
        return $this->valueExpression;
    }

    public function toString(): string
    {
        return "{$this->tokenLiteral()} {$this->name->tokenLiteral()} = {$this->valueExpression->toString()};";
    }
}
