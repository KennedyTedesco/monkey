<?php

declare(strict_types=1);

namespace Monkey\Ast;

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class ReturnStatement extends Statement
{
    private Expression $returnValue;

    public function __construct(
        Token $token,
        Expression $returnValue
    ) {
        $this->token = $token;
        $this->returnValue = $returnValue;
    }

    public function returnValue(): Expression
    {
        return $this->returnValue;
    }

    public function toString(): string
    {
        $out = "{$this->tokenLiteral()} ";
        if (null !== $this->returnValue) {
            $out .= $this->returnValue->toString();
        }
        $out .= ';';
        return $out;
    }
}
