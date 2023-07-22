<?php

declare(strict_types=1);

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Token\Token;

final class LetStatement extends Statement
{
    public function __construct(
        public Token $token,
        public readonly IdentifierExpression $name,
        public readonly Expression $value,
    ) {
    }

    public function toString(): string
    {
        return "{$this->tokenLiteral()} {$this->name->tokenLiteral()} = {$this->value->toString()};";
    }
}
