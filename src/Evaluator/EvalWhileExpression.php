<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\WhileExpression;
use Monkey\Object\BooleanObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final class EvalWhileExpression
{
    public function __construct(private Evaluator $evaluator, private Environment $env)
    {
    }

    public function __invoke(WhileExpression $expression): MonkeyObject
    {
        while (true) {
            $condition = $this->evaluator->eval($expression->condition(), $this->env);
            if ($condition instanceof ErrorObject) {
                return $condition;
            }

            if ((bool) $condition->value()) {
                $evaluated = $this->evaluator->eval($expression->consequence(), $this->env);
                if ($evaluated instanceof ErrorObject) {
                    return $condition;
                }

                continue;
            }

            break;
        }

        return BooleanObject::true();
    }
}
