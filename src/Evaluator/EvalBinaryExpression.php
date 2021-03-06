<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\ArrayObject;
use Monkey\Object\BooleanObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\FloatObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\StringObject;

final class EvalBinaryExpression
{
    public function __invoke(
        string $operator,
        MonkeyObject $left,
        MonkeyObject $right
    ): MonkeyObject {
        switch (true) {
            case $left instanceof ErrorObject:
                return $left;

            case $right instanceof ErrorObject:
                return $right;

            case $left->type() !== $right->type():
                return ErrorObject::typeMismatch($left->typeLiteral(), $operator, $right->typeLiteral());

            case $left instanceof IntegerObject && $right instanceof IntegerObject:
            case $left instanceof FloatObject && $right instanceof FloatObject:
                return (new EvalNumericBinaryExpression())($operator, $left, $right);

            case $left instanceof StringObject && $right instanceof StringObject:
                return (new EvalStringBinaryExpression())($operator, $left, $right);

            case $left instanceof ArrayObject && $right instanceof ArrayObject:
                return (new EvalArrayBinaryExpression())($operator, $left, $right);

            case '&&' === $operator:
                return BooleanObject::from($left->value() && $right->value());

            case '||' === $operator:
                return BooleanObject::from($left->value() || $right->value());

            case '==' === $operator:
                return BooleanObject::from($left->value() === $right->value());

            case '!=' === $operator:
                return BooleanObject::from($left->value() !== $right->value());

            default:
                return ErrorObject::unknownOperator($left->typeLiteral(), $operator, $right->typeLiteral());
        }
    }
}
