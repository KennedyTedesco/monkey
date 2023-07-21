<?php

declare(strict_types=1);

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Token\Token;

final class AssignStatement extends Statement
{
    public function __construct(
        Token $token,
        private readonly IdentifierExpression $identifierExpression,
        private readonly Expression $expression,
    ) {
        $this->token = $token;
    }

    public function name(): IdentifierExpression
    {
        return $this->identifierExpression;
    }

    public function value(): Expression
    {
        return $this->expression;
    }

    public function toString(): string
    {
        return "{$this->identifierExpression->tokenLiteral()} = {$this->expression->toString()};";
    }
}
