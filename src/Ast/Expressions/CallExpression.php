<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Token\Token;

final class CallExpression extends Expression
{
    public function __construct(
        Token $token,
        public readonly Expression $function,
        /* @var array<Expression> */
        public readonly array $arguments,
    ) {
        $this->token = $token;
    }

    public function toString(): string
    {
        $out = "{$this->function->toString()}(";

        $args = [];
        /** @var Expression $argument */
        foreach ($this->arguments as $argument) {
            $args[] = $argument->toString();
        }

        if ($args !== []) {
            $out .= implode(', ', $args);
        }

        return $out . ')';
    }
}
