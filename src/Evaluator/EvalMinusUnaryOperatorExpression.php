<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\IntegerObject;
use MonkeyLang\Object\MonkeyObject;

final class EvalMinusUnaryOperatorExpression
{
    public function __invoke(MonkeyObject $monkeyObject): MonkeyObject
    {
        if (!$monkeyObject instanceof IntegerObject) {
            return ErrorObject::unknownOperator("-{$monkeyObject->typeLiteral()}");
        }

        return new IntegerObject($monkeyObject->value * (-1));
    }
}
