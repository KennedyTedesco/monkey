<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\IntegerObject;
use MonkeyLang\Lang\Object\MonkeyObject;

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
