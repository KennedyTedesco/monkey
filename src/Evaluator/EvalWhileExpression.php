<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\WhileExpression;
use Monkey\Object\BooleanObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final class EvalWhileExpression
{
    public function __construct(private Evaluator $evaluator, private Environment $environment)
    {
    }

    public function __invoke(WhileExpression $whileExpression): MonkeyObject
    {
        while (true) {
            $condition = $this->evaluator->eval($whileExpression->condition(), $this->environment);
            if ($condition instanceof ErrorObject) {
                return $condition;
            }

            if ((bool) $condition->value()) {
                $evaluated = $this->evaluator->eval($whileExpression->consequence(), $this->environment);
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
