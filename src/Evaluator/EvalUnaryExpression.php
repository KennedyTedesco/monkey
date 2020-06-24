<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\ErrorObject;
use Monkey\Object\InternalObject;

final class EvalUnaryExpression
{
    public function __invoke(string $operator, InternalObject $right): InternalObject
    {
        switch (true) {
            case $right instanceof ErrorObject:
                return $right;

            case '!' === $operator:
                return (new EvalNotOperatorExpression())($right);

            case '-' === $operator:
                return (new EvalMinusUnaryOperatorExpression())($right);

            default:
                return ErrorObject::unknownOperator($operator, $right->type());
        }
    }
}
