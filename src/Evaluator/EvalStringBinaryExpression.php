<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\ErrorObject;
use Monkey\Object\InternalObject;
use Monkey\Object\StringObject;

final class EvalStringBinaryExpression
{
    public function __invoke(
        string $operator,
        StringObject $left,
        StringObject $right
    ): InternalObject {
        if ('+' !== $operator) {
            return ErrorObject::unknownOperator($left->type(), $operator, $right->type());
        }

        return new StringObject("{$left->value()}{$right->value()}");
    }
}
