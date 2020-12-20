<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Token\Token;

final class IndexExpression extends Expression
{
    public function __construct(
        Token $token,
        private Expression $left,
        private Expression $index
    ) {
        $this->token = $token;
    }

    public function left(): Expression
    {
        return $this->left;
    }

    public function index(): Expression
    {
        return $this->index;
    }

    public function toString(): string
    {
        return "({$this->left->toString()}[{$this->index->toString()}])";
    }
}
