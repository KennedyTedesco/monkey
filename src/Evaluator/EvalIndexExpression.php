<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Ast\Expressions\IndexExpression;
use MonkeyLang\Object\ArrayObject;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\IntegerObject;
use MonkeyLang\Object\MonkeyObject;
use MonkeyLang\Object\NullObject;

final readonly class EvalIndexExpression
{
    public function __construct(
        public Evaluator $evaluator,
        public Environment $environment,
    ) {
    }

    public function __invoke(IndexExpression $indexExpression): MonkeyObject
    {
        $monkeyObject = $this->evaluator->eval($indexExpression->left, $this->environment);

        if ($monkeyObject instanceof ErrorObject) {
            return $monkeyObject;
        }

        $index = $this->evaluator->eval($indexExpression->index, $this->environment);

        if ($index instanceof ErrorObject) {
            return $index;
        }

        if ($monkeyObject instanceof ArrayObject && $index instanceof IntegerObject) {
            return $monkeyObject->value[$index->value] ?? NullObject::instance();
        }

        return ErrorObject::invalidIndexOperator($index->typeLiteral());
    }
}
