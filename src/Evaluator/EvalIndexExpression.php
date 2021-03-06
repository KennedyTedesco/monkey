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
    public function __construct(private Evaluator $evaluator, private Environment $env)
    {
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
