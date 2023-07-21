<?php

declare(strict_types=1);

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class ExpressionStatement extends Statement
{
    public function __construct(
        Token $token,
        private readonly Expression $expression,
    ) {
        $this->token = $token;
    }

    public function expression(): Expression
    {
        return $this->expression;
    }

    public function toString(): string
    {
        return $this->expression->toString();
    }
}
