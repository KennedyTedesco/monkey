<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Ast\Statements\BlockStatement;
use Monkey\Token\Token;

final class IfExpression extends Expression
{
    public function __construct(
        Token $token,
        public readonly Expression $condition,
        public readonly BlockStatement $consequence,
        public readonly ?BlockStatement $alternative = null,
    ) {
        $this->token = $token;
    }

    public function toString(): string
    {
        $out = "if{$this->condition->toString()} {$this->consequence->toString()}";

        if ($this->alternative instanceof BlockStatement) {
            $out .= "else {$this->alternative->toString()}";
        }

        return $out;
    }
}
