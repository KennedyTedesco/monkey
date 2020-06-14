<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\BooleanObject;
use Monkey\Object\InternalObject;
use Monkey\Object\NullObject;

final class EvalBinaryExpression
{
    public function __invoke(
        string $operator,
        InternalObject $left,
        InternalObject $right
    ): InternalObject {
        if (InternalObject::INTEGER_OBJ === $left->type() && InternalObject::INTEGER_OBJ === $right->type()) {
            return (new EvalIntegerBinaryExpression())($operator, $left, $right);
        }

        switch ($operator) {
            case '!=':
                return BooleanObject::from($left->value() !== $right->value());
            case '==':
                return BooleanObject::from($left->value() === $right->value());
            default:
                return NullObject::instance();
        }
    }
}
