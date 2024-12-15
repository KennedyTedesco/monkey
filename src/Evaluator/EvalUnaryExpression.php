<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\MonkeyObject;

final class EvalUnaryExpression
{
    public function __invoke(string $operator, MonkeyObject $monkeyObject): MonkeyObject
    {
        return match (true) {
            $monkeyObject instanceof ErrorObject => $monkeyObject,
            $operator === '!' => new EvalNotOperatorExpression()($monkeyObject),
            $operator === '-' => new EvalMinusUnaryOperatorExpression()($monkeyObject),
            default => ErrorObject::unknownOperator($operator, $monkeyObject->typeLiteral()),
        };
    }
}
