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
        public readonly IdentifierExpression $name,
        public readonly Expression $value,
    ) {
        $this->token = $token;
    }

    public function toString(): string
    {
        return "{$this->name->tokenLiteral()} = {$this->value->toString()};";
    }
}
