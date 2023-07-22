<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Ast\Statements\BlockStatement;
use Monkey\Token\Token;

final class WhileExpression extends Expression
{
    public function __construct(
        Token $token,
        public readonly Expression $condition,
        public readonly BlockStatement $consequence,
    ) {
        $this->token = $token;
    }

    public function toString(): string
    {
        return "while{$this->condition->toString()} {$this->consequence->toString()}";
    }
}
