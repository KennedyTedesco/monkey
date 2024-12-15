<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Object\BooleanObject;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Object\StringObject;

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
