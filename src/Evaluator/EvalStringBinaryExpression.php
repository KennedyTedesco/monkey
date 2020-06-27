<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\BooleanObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\StringObject;

final class EvalStringBinaryExpression
{
    public function __invoke(
        string $operator,
        StringObject $left,
        StringObject $right
    ): MonkeyObject {
        switch ($operator) {
            case '+':
                return new StringObject("{$left->value()}{$right->value()}");
            case '!=':
                return BooleanObject::from($left->value() !== $right->value());
            case '==':
                return BooleanObject::from($left->value() === $right->value());
            default:
                return ErrorObject::unknownOperator($left->type(), $operator, $right->type());
        }
    }
}
