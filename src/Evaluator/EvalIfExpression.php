<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\IfExpression;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;

final class EvalIfExpression
{
    public function __construct(
        private Evaluator $evaluator,
        private Environment $env
    ) {
    }

    public function __invoke(IfExpression $expression): MonkeyObject
    {
        $condition = $this->evaluator->eval($expression->condition(), $this->env);

        return match (true) {
            $condition instanceof ErrorObject => $condition,
            (bool) $condition->value() => $this->evaluator->eval($expression->consequence(), $this->env),
            null !== $expression->alternative() => $this->evaluator->eval($expression->alternative(), $this->env),
            default => NullObject::instance(),
        };
    }
}
