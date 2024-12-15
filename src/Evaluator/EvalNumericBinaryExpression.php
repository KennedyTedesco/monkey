<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Object\BooleanObject;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\MonkeyObject;

final class EvalNumericBinaryExpression
{
    public function __invoke(
        string $operator,
        MonkeyObject $left,
        MonkeyObject $right,
    ): MonkeyObject {
        return match ($operator) {
            '+' => new $left($left->value() + $right->value()),
            '-' => new $left($left->value() - $right->value()),
            '*' => new $left($left->value() * $right->value()),
            '%' => new $left($left->value() % $right->value()),
            '**' => new $left($left->value() ** $right->value()),
            '/' => new $left($left->value() / $right->value()),
            '<' => BooleanObject::from($left->value() < $right->value()),
            '>' => BooleanObject::from($left->value() > $right->value()),
            '<=' => BooleanObject::from($left->value() <= $right->value()),
            '>=' => BooleanObject::from($left->value() >= $right->value()),
            '!=' => BooleanObject::from($left->value() !== $right->value()),
            '==' => BooleanObject::from($left->value() === $right->value()),
            default => ErrorObject::unknownOperator($left->typeLiteral(), $operator, $right->typeLiteral()),
        };
    }
}
