<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\MonkeyObject;

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
