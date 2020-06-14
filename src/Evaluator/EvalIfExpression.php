<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\IfExpression;
use Monkey\Object\InternalObject;
use Monkey\Object\NullObject;
use Monkey\Object\ObjectUtils;

final class EvalIfExpression
{
    private Evaluator $evaluator;

    public function __construct(Evaluator $evaluator)
    {
        $this->evaluator = $evaluator;
    }

    public function __invoke(IfExpression $expression): InternalObject
    {
        $condition = $this->evaluator->eval($expression->condition());

        if (ObjectUtils::isTruthy($condition)) {
            return $this->evaluator->eval($expression->consequence());
        }

        if (null !== $expression->alternative()) {
            return $this->evaluator->eval($expression->alternative());
        }

        return NullObject::instance();
    }
}
