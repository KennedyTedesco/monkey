<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Object\ArrayObject;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\MonkeyObject;

final class EvalArrayBinaryExpression
{
    public function __invoke(
        string $operator,
        ArrayObject $left,
        ArrayObject $right,
    ): MonkeyObject {
        return match ($operator) {
            '+' => new ArrayObject(array_merge($left->value(), $right->value())),
            default => ErrorObject::unknownOperator($left->typeLiteral(), $operator, $right->typeLiteral()),
        };
    }
}
