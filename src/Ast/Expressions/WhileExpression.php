<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Ast\Statements\BlockStatement;
use Monkey\Support\StringBuilder;
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
        return StringBuilder::new("while")
            ->append($this->condition)
            ->appendSpace()
            ->append($this->consequence)
            ->toString();
    }
}
