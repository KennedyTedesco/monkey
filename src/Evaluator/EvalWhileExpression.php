<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Ast\Expressions\WhileExpression;
use MonkeyLang\Object\BooleanObject;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\MonkeyObject;

final readonly class EvalWhileExpression
{
    public function __construct(
        public Evaluator $evaluator,
        public Environment $environment,
    ) {
    }

    public function __invoke(WhileExpression $whileExpression): MonkeyObject
    {
        while (true) {
            $condition = $this->evaluator->eval($whileExpression->condition, $this->environment);

            if ($condition instanceof ErrorObject) {
                return $condition;
            }

            if ($condition->value()) {
                $evaluated = $this->evaluator->eval($whileExpression->consequence, $this->environment);

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
