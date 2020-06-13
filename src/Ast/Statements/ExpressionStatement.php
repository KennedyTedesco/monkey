<?php

declare(strict_types=1);

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class ExpressionStatement extends Statement
{
    private Expression $expression;

    public function __construct(Token $token, Expression $expression)
    {
        $this->token = $token;
        $this->expression = $expression;
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
