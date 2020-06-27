<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\IndexExpression;
use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;

final class EvalIndexExpression
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

    public function __invoke(IndexExpression $node): MonkeyObject
    {
        $left = $this->evaluator->eval($node->left(), $this->env);

        if ($left instanceof ErrorObject) {
            return $left;
        }

        $index = $this->evaluator->eval($node->index(), $this->env);

        if ($index instanceof ErrorObject) {
            return $index;
        }

        if ($left instanceof ArrayObject && $index instanceof IntegerObject) {
            return $left->value()[$index->value()] ?? NullObject::instance();
        }

        return ErrorObject::invalidIndexOperator($index->typeLiteral());
    }
}
