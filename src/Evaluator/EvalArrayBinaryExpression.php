<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Object\ArrayObject;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\MonkeyObject;

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
