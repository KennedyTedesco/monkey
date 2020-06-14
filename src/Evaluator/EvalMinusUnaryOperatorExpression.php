<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\InternalObject;

final class EvalMinusUnaryOperatorExpression
{
    public function __invoke(InternalObject $right): InternalObject
    {
        if (InternalObject::INTEGER_OBJ !== $right->type()) {
            return ErrorObject::unknownOperator("-{$right->type()}");
        }

        return new IntegerObject($right->value() * (-1));
    }
}
