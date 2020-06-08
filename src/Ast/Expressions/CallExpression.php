<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Token\Token;

final class CallExpression extends Expression
{
    private Expression $function;

    /** @var array<Expression> */
    private array $arguments;

    public function __construct(
        Token $token,
        Expression $function,
        array $arguments
    ) {
        $this->token = $token;
        $this->function = $function;
        $this->arguments = $arguments;
    }

    public function function(): Expression
    {
        return $this->function;
    }

    public function arguments(): array
    {
        return $this->arguments;
    }

    public function toString(): string
    {
        $out = "{$this->function->toString()}(";

        $args = [];
        /** @var Expression $argument */
        foreach ($this->arguments as $argument) {
            $args[] = $argument->toString();
        }

        if (\count($args) > 0) {
            $out .= \implode(', ', $args);
        }

        $out .= ')';

        return $out;
    }
}
