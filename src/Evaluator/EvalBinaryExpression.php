<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\BooleanObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\FloatObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\InternalObject;
use Monkey\Object\StringObject;

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

            case $left instanceof IntegerObject && $right instanceof IntegerObject:
            case $left instanceof FloatObject && $right instanceof FloatObject:
                return (new EvalNumericBinaryExpression())($operator, $left, $right);

            case $left instanceof StringObject && $right instanceof StringObject:
                return (new EvalStringBinaryExpression())($operator, $left, $right);

            case '&&' === $operator:
                return BooleanObject::from($left->value() && $right->value());

            case '||' === $operator:
                return BooleanObject::from($left->value() || $right->value());

            case '==' === $operator:
                return BooleanObject::from($left->value() === $right->value());

            case '!=' === $operator:
                return BooleanObject::from($left->value() !== $right->value());

            default:
                return ErrorObject::unknownOperator($left->type(), $operator, $right->type());
        }
    }
}
