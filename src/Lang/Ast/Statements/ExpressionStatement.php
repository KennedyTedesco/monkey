<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Ast\Statements;

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Token\Token;

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
