<?php

declare(strict_types=1);

namespace Monkey\Ast;

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class ReturnStatement extends Statement
{
    public function __construct(
        Token $token,
        private Expression $returnValue
    ) {
        $this->token = $token;
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
