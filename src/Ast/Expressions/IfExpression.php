<?php

declare(strict_types=1);

namespace MonkeyLang\Ast\Expressions;

use MonkeyLang\Ast\Statements\BlockStatement;
use MonkeyLang\Support\StringBuilder;
use MonkeyLang\Token\Token;

final class IfExpression extends Expression
{
    public function __construct(
        public readonly Token $token,
        public readonly Expression $condition,
        public readonly BlockStatement $consequence,
        public readonly ?BlockStatement $alternative = null,
    ) {
    }

    public function toString(): string
    {
        $builder = StringBuilder::new('if')
            ->append($this->condition)
            ->append(' ')
            ->append($this->consequence);

        if ($this->alternative instanceof BlockStatement) {
            $builder->append('else ')
                ->append($this->alternative);
        }

        return $builder->toString();
    }
}
