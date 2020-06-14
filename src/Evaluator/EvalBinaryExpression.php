<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\BooleanObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\InternalObject;

final class EvalBinaryExpression
{
    public function __invoke(
        string $operator,
        InternalObject $left,
        InternalObject $right
    ): InternalObject {
        if ($left->type() !== $right->type()) {
            return ErrorObject::typeMismatch($left->type(), $operator, $right->type());
        }

        if (InternalObject::INTEGER_OBJ === $left->type()) {
            return (new EvalIntegerBinaryExpression())($operator, $left, $right);
        }

        switch ($operator) {
            case '!=':
                return BooleanObject::from($left->value() !== $right->value());
            case '==':
                return BooleanObject::from($left->value() === $right->value());
            default:
                return ErrorObject::unknownOperator($left->type(), $operator, $right->type());
        }
    }
}
