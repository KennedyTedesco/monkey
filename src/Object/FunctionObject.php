<?php

declare(strict_types=1);

namespace MonkeyLang\Object;

use MonkeyLang\Ast\Expressions\IdentifierExpression;
use MonkeyLang\Ast\Statements\BlockStatement;
use MonkeyLang\Evaluator\Environment;

use function sprintf;

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

    public function type(): MonkeyObjectType
    {
        return MonkeyObjectType::FUNCTION;
    }

    public function inspect(): string
    {
        $params = [];

        foreach ($this->parameters as $parameter) {
            $params[] = $parameter->toString();
        }

        return sprintf("fn(%s) {\n%s\n}", implode(', ', $params), $this->body->toString());
    }

    public function value(): mixed
    {
        return null;
    }
}
