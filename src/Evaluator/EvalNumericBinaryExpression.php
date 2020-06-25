<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\BooleanObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\InternalObject;

final class EvalNumericBinaryExpression
{
    public function __invoke(
        string $operator,
        InternalObject $left,
        InternalObject $right
    ): InternalObject {
        switch ($operator) {
            case '+':
                return new $left($left->value() + $right->value());
            case '-':
                return new $left($left->value() - $right->value());
            case '*':
                return new $left($left->value() * $right->value());
            case '%':
                return new $left($left->value() % $right->value());
            case '**':
                return new $left($left->value() ** $right->value());
            case '/':
                return new $left($left->value() / $right->value());
            case '<':
                return BooleanObject::from($left->value() < $right->value());
            case '>':
                return BooleanObject::from($left->value() > $right->value());
            case '<=':
                return BooleanObject::from($left->value() <= $right->value());
            case '>=':
                return BooleanObject::from($left->value() >= $right->value());
            case '!=':
                return BooleanObject::from($left->value() !== $right->value());
            case '==':
                return BooleanObject::from($left->value() === $right->value());
            default:
                return ErrorObject::unknownOperator($left->type(), $operator, $right->type());
        }
    }
}
