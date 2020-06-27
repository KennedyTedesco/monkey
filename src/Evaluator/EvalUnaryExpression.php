<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final class EvalUnaryExpression
{
    public function __invoke(string $operator, MonkeyObject $right): MonkeyObject
    {
        switch (true) {
            case $right instanceof ErrorObject:
                return $right;

            case '!' === $operator:
                return (new EvalNotOperatorExpression())($right);

            case '-' === $operator:
                return (new EvalMinusUnaryOperatorExpression())($right);

            default:
                return ErrorObject::unknownOperator($operator, $right->typeLiteral());
        }
    }
}
