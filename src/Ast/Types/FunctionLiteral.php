<?php

declare(strict_types=1);

namespace Monkey\Ast\Types;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Ast\Statements\BlockStatement;
use Monkey\Ast\Statements\Statement;
use Monkey\Token\Token;

final class FunctionLiteral extends Expression
{
    public function __construct(Token $token, /* @var array<IdentifierExpression> */
    private array $parameters, private BlockStatement $blockStatement)
    {
        $this->token = $token;
    }

    public function body(): BlockStatement
    {
        return $this->blockStatement;
    }

    /**
     * @return array<IdentifierExpression>
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    public function toString(): string
    {
        $out = "{$this->tokenLiteral()}(";

        $params = [];
        /** @var Statement $parameter */
        foreach ($this->parameters as $parameter) {
            $params[] = $parameter->toString();
        }

        if ([] !== $params) {
            $out .= implode(',', $params);
        }

        return $out.") {$this->blockStatement->toString()}";
    }
}
