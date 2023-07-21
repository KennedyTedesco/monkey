<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Token\Token;

final class CallExpression extends Expression
{
    public function __construct(
        Token $token,
        private readonly Expression $expression,
        /* @var array<Expression> */
        private readonly array $arguments,
    ) {
        $this->token = $token;
    }

    public function function(): Expression
    {
        return $this->expression;
    }

    /**
     * @return mixed[]
     */
    public function arguments(): array
    {
        return $this->arguments;
    }

    public function toString(): string
    {
        $out = "{$this->expression->toString()}(";

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
