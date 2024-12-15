<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Ast\Expressions\IndexExpression;
use MonkeyLang\Lang\Object\ArrayObject;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\IntegerObject;
use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Object\NullObject;

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
