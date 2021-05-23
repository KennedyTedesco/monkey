<?php

declare(strict_types=1);

namespace Monkey\Object;

use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Ast\Statements\BlockStatement;
use Monkey\Evaluator\Environment;

final class FunctionObject extends MonkeyObject
{
    public function __construct(
        /* @var array<IdentifierExpression> */
        private array $parameters,
        private BlockStatement $body,
        private Environment $environment
    ) {
    }

    public function environment(): Environment
    {
        return $this->environment;
    }

    /**
     * @return array<IdentifierExpression>
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    public function parameter(int $index): IdentifierExpression
    {
        return $this->parameters[$index];
    }

    public function body(): BlockStatement
    {
        return $this->body;
    }

    public function type(): int
    {
        return self::MO_FUNCTION;
    }

    public function typeLiteral(): string
    {
        return 'FUNCTION';
    }

    public function inspect(): string
    {
        $params = [];
        /** @var IdentifierExpression $parameter */
        foreach ($this->parameters as $parameter) {
            $params[] = $parameter->toString();
        }

        return sprintf("fn(%s) {\n%s\n}", implode(', ', $params), $this->body->toString());
    }
}
