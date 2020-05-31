<?php

declare(strict_types=1);

namespace Monkey\Ast;

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class ReturnStatement extends Statement
{
    private ?Expression $value;

    public function __construct(Token $token, ?Expression $value = null)
    {
        $this->token = $token;
        $this->value = $value;
    }

    public function toString(): string
    {
        $out = "{$this->tokenLiteral()} ";
        if (null !== $this->value) {
            $out .= $this->value->toString();
        }
        $out .= ';';
        return $out;
    }
}
