<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

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
        return "({$this->left->toString()}[{$this->index->toString()}])";
    }
}
