<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\BooleanObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\InternalObject;

final class EvalBinaryExpression
{
    public function __invoke(
        string $operator,
        InternalObject $left,
        InternalObject $right
    ): InternalObject {
        switch (true) {
            case $left instanceof ErrorObject:
                return $left;

            case $right instanceof ErrorObject:
                return $right;

            case $left->type() !== $right->type():
                return ErrorObject::typeMismatch($left->type(), $operator, $right->type());

            case $left instanceof IntegerObject:
                return (new EvalIntegerBinaryExpression())($operator, $left, $right);

            case '==' === $operator:
                return BooleanObject::from($left->value() === $right->value());

            case '!=' === $operator:
                return BooleanObject::from($left->value() !== $right->value());

            default:
                return ErrorObject::unknownOperator($left->type(), $operator, $right->type());
        }
    }
}
