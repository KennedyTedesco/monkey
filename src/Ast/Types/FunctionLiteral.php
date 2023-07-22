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
    public function __construct(
        Token $token,
        /* @var array<IdentifierExpression> */
        public readonly array $parameters,
        public readonly BlockStatement $body,
    ) {
        $this->token = $token;
    }

    public function body(): BlockStatement
    {
        return $this->body;
    }

    public function toString(): string
    {
        $out = "{$this->tokenLiteral()}(";

        $params = [];
        /** @var Statement $parameter */
        foreach ($this->parameters as $parameter) {
            $params[] = $parameter->toString();
        }

        if ($params !== []) {
            $out .= implode(',', $params);
        }

        return $out . ") {$this->body->toString()}";
    }
}
