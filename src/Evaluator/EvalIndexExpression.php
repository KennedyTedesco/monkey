<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\IndexExpression;
use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;

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
