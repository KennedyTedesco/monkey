<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\IfExpression;
use Monkey\Object\ErrorObject;
use Monkey\Object\InternalObject;
use Monkey\Object\NullObject;
use Monkey\Object\ObjectUtils;

final class EvalIfExpression
{
    private Environment $env;
    private Evaluator $evaluator;

    public function __construct(
        Evaluator $evaluator,
        Environment $env
    ) {
        $this->env = $env;
        $this->evaluator = $evaluator;
    }

    public function __invoke(IfExpression $expression): InternalObject
    {
        $condition = $this->evaluator->eval($expression->condition(), $this->env);

        switch (true) {
            case $condition instanceof ErrorObject:
                return $condition;

            case ObjectUtils::isTruthy($condition):
                return $this->evaluator->eval($expression->consequence(), $this->env);

            case null !== $expression->alternative():
                return $this->evaluator->eval($expression->alternative(), $this->env);

            default:
                return NullObject::instance();
        }
    }
}
