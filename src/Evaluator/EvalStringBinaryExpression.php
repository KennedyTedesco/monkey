<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Object\BooleanObject;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\MonkeyObject;
use MonkeyLang\Object\StringObject;

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
