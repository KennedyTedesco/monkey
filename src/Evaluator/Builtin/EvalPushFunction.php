<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final class EvalPushFunction
{
    public function __invoke(MonkeyObject ...$monkeyObject): MonkeyObject
    {
        if (2 !== \count($monkeyObject)) {
            return ErrorObject::wrongNumberOfArguments(\count($monkeyObject), 2);
        }

        $object = $monkeyObject[0];
        if (!$object instanceof ArrayObject) {
            return ErrorObject::invalidArgument('push()', $object->typeLiteral());
        }

        $elements = $object->value();
        $elements[] = $monkeyObject[1];

        return new ArrayObject($elements);
    }
}
