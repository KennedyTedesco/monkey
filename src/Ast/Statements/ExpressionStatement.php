<?php

declare(strict_types=1);

namespace MonkeyLang\Ast\Statements;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Token\Token;

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
