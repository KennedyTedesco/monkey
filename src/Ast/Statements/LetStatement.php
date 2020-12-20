<?php

declare(strict_types=1);

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Token\Token;

final class LetStatement extends Statement
{
    public function __construct(
        Token $token,
        private IdentifierExpression $name,
        private Expression $value
    ) {
        $this->token = $token;
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
        return "{$this->tokenLiteral()} {$this->name->tokenLiteral()} = {$this->value->toString()};";
    }
}
