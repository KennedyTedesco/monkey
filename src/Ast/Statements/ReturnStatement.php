<?php

declare(strict_types=1);

namespace Monkey\Ast;

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class ReturnStatement extends Statement
{
    private Expression $valueExpression;

    public function __construct(
        Token $token,
        Expression $valueExpression
    ) {
        $this->token = $token;
        $this->valueExpression = $valueExpression;
    }

    public function valueExpression(): Expression
    {
        return $this->valueExpression;
    }

    public function toString(): string
    {
        $out = "{$this->tokenLiteral()} ";
        if (null !== $this->valueExpression) {
            $out .= $this->valueExpression->toString();
        }
        $out .= ';';
        return $out;
    }
}
