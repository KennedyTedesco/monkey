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
    /** @var array<IdentifierExpression> */
    private array $parameters;
    private BlockStatement $body;

    public function __construct(Token $token, array $parameters, BlockStatement $body)
    {
        $this->body = $body;
        $this->token = $token;
        $this->parameters = $parameters;
    }

    public function body(): BlockStatement
    {
        return $this->body;
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

        if (\count($params) > 0) {
            $out .= \implode(',', $params);
        }

        $out .= "){$this->body->toString()}";

        return $out;
    }
}
