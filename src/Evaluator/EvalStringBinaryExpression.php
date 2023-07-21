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
        StringObject $right,
    ): MonkeyObject {
        return match ($operator) {
            '+' => new StringObject("{$left->value()}{$right->value()}"),
            '!=' => BooleanObject::from($left->value() !== $right->value()),
            '==' => BooleanObject::from($left->value() === $right->value()),
            default => ErrorObject::unknownOperator($left->typeLiteral(), $operator, $right->typeLiteral()),
        };
    }
}
