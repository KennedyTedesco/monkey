<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\WhileExpression;
use Monkey\Object\BooleanObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\ObjectUtils;

final class EvalWhileExpression
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

    public function __invoke(WhileExpression $expression): MonkeyObject
    {
        while (true) {
            $condition = $this->evaluator->eval($expression->condition(), $this->env);
            if ($condition instanceof ErrorObject) {
                return $condition;
            }

            if (ObjectUtils::isTruthy($condition)) {
                $evaluated = $this->evaluator->eval($expression->consequence(), $this->env);
                if ($evaluated instanceof ErrorObject) {
                    return $condition;
                }

                continue;
            }

            break;
        }

        return BooleanObject::from(true);
    }
}
