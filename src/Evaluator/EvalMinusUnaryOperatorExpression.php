<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\IntegerObject;
use Monkey\Object\InternalObject;
use Monkey\Object\NullObject;

final class EvalMinusUnaryOperatorExpression
{
    public function __invoke(InternalObject $right): InternalObject
    {
        if (InternalObject::INTEGER_OBJ !== $right->type()) {
            return NullObject::instance();
        }

        return new IntegerObject($right->value() * (-1));
    }
}
