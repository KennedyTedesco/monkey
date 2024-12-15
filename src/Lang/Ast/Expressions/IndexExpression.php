<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Ast\Expressions;

use MonkeyLang\Lang\Support\StringBuilder;
use MonkeyLang\Lang\Token\Token;

final class IndexExpression extends Expression
{
    public function __construct(
        public readonly Token $token,
        public readonly Expression $left,
        public readonly Expression $index,
    ) {
    }

    public function toString(): string
    {
        return StringBuilder::new()
            ->append('(')
            ->append($this->left)
            ->append('[')
            ->append($this->index)
            ->append('])')
            ->toString();
    }
}
