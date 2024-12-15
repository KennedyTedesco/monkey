<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Ast\Expressions;

use MonkeyLang\Lang\Ast\Statements\BlockStatement;
use MonkeyLang\Lang\Support\StringBuilder;
use MonkeyLang\Lang\Token\Token;

final class WhileExpression extends Expression
{
    public function __construct(
        public readonly Token $token,
        public readonly Expression $condition,
        public readonly BlockStatement $consequence,
    ) {
    }

    public function toString(): string
    {
        return StringBuilder::new('while')
            ->append($this->condition)
            ->appendSpace()
            ->append($this->consequence)
            ->toString();
    }
}
