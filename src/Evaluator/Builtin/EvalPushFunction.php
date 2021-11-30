<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final class EvalPushFunction
{
    public function __invoke(MonkeyObject ...$arguments): MonkeyObject
    {
        if (2 !== \count($arguments)) {
            return ErrorObject::wrongNumberOfArguments(\count($arguments), 2);
        }

        $object = $arguments[0];
        if (!$object instanceof ArrayObject) {
            return ErrorObject::invalidArgument('push()', $object->typeLiteral());
        }

        $elements = $object->value();
        $elements[] = $arguments[1];

        return new ArrayObject($elements);
    }
}
