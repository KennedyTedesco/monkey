<?php

declare(strict_types=1);

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Token\Token;

final class AssignStatement extends Statement
{
    private Expression $value;
    private IdentifierExpression $name;

    public function __construct(
        Token $token,
        IdentifierExpression $name,
        Expression $value
    ) {
        $this->token = $token;
        $this->name = $name;
        $this->value = $value;
    }

    public function name(): IdentifierExpression
    {
        return $this->name;
    }

    public function value(): Expression
    {
        return $this->value;
    }

    public function toString(): string
    {
        return "{$this->name->tokenLiteral()} = {$this->value->toString()};";
    }
}
