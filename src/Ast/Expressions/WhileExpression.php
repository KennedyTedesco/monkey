<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Ast\Statements\BlockStatement;
use Monkey\Token\Token;

final class WhileExpression extends Expression
{
    public function __construct(
        Token $token,
        private Expression $expression,
        private BlockStatement $blockStatement
    ) {
        $this->token = $token;
    }

    public function condition(): Expression
    {
        return $this->expression;
    }

    public function consequence(): BlockStatement
    {
        return $this->blockStatement;
    }

    public function toString(): string
    {
        return "while{$this->expression->toString()} {$this->blockStatement->toString()}";
    }
}
