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
        public readonly Expression $value,
    ) {
        $this->token = $token;
    }

    public function toString(): string
    {
        $out = "{$this->tokenLiteral()} ";
        $out .= $this->value->toString();

        return $out . ';';
    }
}
