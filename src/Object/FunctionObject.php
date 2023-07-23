<?php

declare(strict_types=1);

namespace Monkey\Object;

use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Ast\Statements\BlockStatement;
use Monkey\Evaluator\Environment;

final readonly class FunctionObject extends MonkeyObject
{
    /**
     * @param array<IdentifierExpression> $parameters
     */
    public function __construct(
        /* @var array<IdentifierExpression> */
        public array $parameters,
        public BlockStatement $body,
        public Environment $environment,
    ) {
    }

    public function parameter(int $index): IdentifierExpression
    {
        return $this->parameters[$index];
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

        foreach ($this->parameters as $parameter) {
            $params[] = $parameter->toString();
        }

        return sprintf("fn(%s) {\n%s\n}", implode(', ', $params), $this->body->toString());
    }
}
