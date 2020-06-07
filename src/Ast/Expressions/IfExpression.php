<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Ast\Statements\BlockStatement;
use Monkey\Token\Token;

final class IfExpression extends Expression
{
    private Expression $condition;
    private BlockStatement $consequence;
    private ?BlockStatement $alternative;

    public function __construct(
        Token $token,
        Expression $condition,
        BlockStatement $consequence,
        ?BlockStatement $alternative = null
    ) {
        $this->token = $token;
        $this->condition = $condition;
        $this->consequence = $consequence;
        $this->alternative = $alternative;
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
