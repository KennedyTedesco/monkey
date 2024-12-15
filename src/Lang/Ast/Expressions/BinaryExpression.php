<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Ast\Expressions;

use MonkeyLang\Lang\Support\StringBuilder;
use MonkeyLang\Lang\Token\Token;

final class BinaryExpression extends Expression
{
    public function __construct(
        public readonly Token $token,
        public readonly string $operator,
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }

    public function toString(): string
    {
        return StringBuilder::new()
            ->append('(')
            ->append($this->left)
            ->appendSpace()
            ->append($this->operator)
            ->appendSpace()
            ->append($this->right)
            ->append(')')
            ->toString();
    }
}
