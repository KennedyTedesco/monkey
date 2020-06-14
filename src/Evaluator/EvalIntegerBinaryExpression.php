<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\IntegerObject;
use Monkey\Object\InternalObject;
use Monkey\Object\NullObject;

final class EvalIntegerBinaryExpression
{
    public function __invoke(
        string $operator,
        InternalObject $left,
        InternalObject $right
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
            default:
                return NullObject::null();
        }
    }
}
