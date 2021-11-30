<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Ast\Statements\BlockStatement;
use Monkey\Token\Token;

final class IfExpression extends Expression
{
    public function __construct(
        Token $token,
        private Expression $condition,
        private BlockStatement $consequence,
        private ?BlockStatement $alternative = null
    ) {
        $this->token = $token;
    }

    public function condition(): Expression
    {
        return $this->condition;
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
        $out = "if{$this->condition->toString()} {$this->consequence->toString()}";
        if (null !== $this->alternative) {
            $out .= "else {$this->alternative->toString()}";
        }

        return $out;
    }
}
