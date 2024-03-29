<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Support\StringBuilder;
use Monkey\Token\Token;

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
