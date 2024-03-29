<?php

declare(strict_types=1);

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Support\StringBuilder;
use Monkey\Token\Token;

final class AssignStatement extends Statement
{
    public function __construct(
        public readonly Token $token,
        public readonly IdentifierExpression $name,
        public readonly Expression $value,
    ) {
    }

    public function toString(): string
    {
        return StringBuilder::new()
            ->append($this->token->literal)
            ->append(' = ')
            ->append($this->value->toString())
            ->append(';')
            ->toString();
    }
}
