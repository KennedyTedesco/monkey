<?php

declare(strict_types=1);

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class ExpressionStatement extends Statement
{
    private Expression $value;

    public function __construct(Token $token, Expression $value)
    {
        $this->token = $token;
        $this->value = $value;
    }

    public function value(): Expression
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value->toString();
    }
}
