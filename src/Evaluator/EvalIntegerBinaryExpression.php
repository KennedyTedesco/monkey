<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\BooleanObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\InternalObject;

final class EvalIntegerBinaryExpression
{
    public function __invoke(
        string $operator,
        IntegerObject $left,
        IntegerObject $right
    ): InternalObject {
        switch ($operator) {
            case '+':
                return new IntegerObject($left->value() + $right->value());
            case '-':
                return new IntegerObject($left->value() - $right->value());
            case '*':
                return new IntegerObject($left->value() * $right->value());
            case '/':
                return new IntegerObject($left->value() / $right->value());
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
