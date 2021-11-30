<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Ast\Statements\BlockStatement;
use Monkey\Token\Token;

final class IfExpression extends Expression
{
    public function __construct(
        Token $token,
        private Expression $expression,
        private BlockStatement $consequence,
        private ?BlockStatement $alternative = null
    ) {
        $this->token = $token;
    }

    public function condition(): Expression
    {
        return $this->expression;
    }

    public function consequence(): BlockStatement
    {
        return $this->consequence;
    }

    public function alternative(): ?BlockStatement
    {
        return $this->alternative;
    }

    public function toString(): string
    {
        $out = "if{$this->expression->toString()} {$this->consequence->toString()}";
        if (null !== $this->alternative) {
            $out .= "else {$this->alternative->toString()}";
        }

        return $out;
    }
}
