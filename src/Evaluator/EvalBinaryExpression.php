<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

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

        return NullObject::null();
    }
}
