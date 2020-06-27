<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\MonkeyObject;

final class EvalMinusUnaryOperatorExpression
{
    public function __invoke(MonkeyObject $right): MonkeyObject
    {
        if (!$right instanceof IntegerObject) {
            return ErrorObject::unknownOperator("-{$right->typeLiteral()}");
        }

        return new IntegerObject($right->value() * (-1));
    }
}
