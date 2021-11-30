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
        private Environment $environment
    ) {
    }

    public function __invoke(IfExpression $ifExpression): MonkeyObject
    {
        $condition = $this->evaluator->eval($ifExpression->condition(), $this->environment);

        return match (true) {
            $condition instanceof ErrorObject => $condition,
            (bool) $condition->value() => $this->evaluator->eval($ifExpression->consequence(), $this->environment),
            null !== $ifExpression->alternative() => $this->evaluator->eval($ifExpression->alternative(), $this->environment),
            default => NullObject::instance(),
        };
    }
}
