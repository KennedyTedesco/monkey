<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Support\StringBuilder;
use Monkey\Token\Token;

final class IndexExpression extends Expression
{
    public function __construct(
        Token $token,
        public readonly Expression $left,
        public readonly Expression $index,
    ) {
        $this->token = $token;
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
