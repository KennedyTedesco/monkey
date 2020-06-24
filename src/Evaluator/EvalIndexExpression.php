<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\InternalObject;
use Monkey\Object\NullObject;

final class EvalIndexExpression
{
    public function __invoke(
        InternalObject $left,
        InternalObject $index
    ): InternalObject {
        if ($left instanceof ArrayObject && $index instanceof IntegerObject) {
            return $left->value()[$index->value()] ?? NullObject::instance();
        }

        return ErrorObject::invalidIndexOperator($index->type());
    }
}
