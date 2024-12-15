<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Ast\Expressions;

use MonkeyLang\Lang\Support\StringBuilder;
use MonkeyLang\Lang\Token\Token;

use function count;

final class CallExpression extends Expression
{
    /**
     * @param array<Expression> $arguments
     */
    public function __construct(
        public readonly Token $token,
        public readonly Expression $function,
        public readonly array $arguments,
    ) {
    }

    public function toString(): string
    {
        $stringBuilder = StringBuilder::new($this->function)->append('(');

        $count = count($this->arguments);

        foreach ($this->arguments as $index => $argument) {
            $separator = $index !== $count - 1 ? ', ' : '';

            $stringBuilder->append("{$argument}{$separator}");
        }

        return $stringBuilder->append(')')->toString();
    }
}
