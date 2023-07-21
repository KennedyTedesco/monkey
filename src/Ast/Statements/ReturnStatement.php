<?php

declare(strict_types=1);

namespace Monkey\Ast\Statements;

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class ReturnStatement extends Statement
{
    public function __construct(
        Token $token,
        private readonly Expression $expression,
    ) {
        $this->token = $token;
    }

    public function returnValue(): Expression
    {
        return $this->expression;
    }

    public function toString(): string
    {
        $out = "{$this->tokenLiteral()} ";
        $out .= $this->expression->toString();

        return $out . ';';
    }
}
