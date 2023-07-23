<?php

declare(strict_types=1);

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class ExpressionStatement extends Statement
{
    public function __construct(
        public readonly Token $token,
        public readonly Expression $expression,
    ) {
    }

    public function toString(): string
    {
        return $this->expression->toString();
    }
}
