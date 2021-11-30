<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\StringObject;

final class EvalLenFunction
{
    public function __invoke(MonkeyObject ...$monkeyObject): MonkeyObject
    {
        if (1 !== \count($monkeyObject)) {
            return ErrorObject::wrongNumberOfArguments(\count($monkeyObject), 1);
        }

        $object = $monkeyObject[0];

        return match (true) {
            $object instanceof StringObject => new IntegerObject(mb_strlen($object->value())),
            $object instanceof ArrayObject => new IntegerObject(\count($object->value())),
            default => ErrorObject::invalidArgument('len()', $object->typeLiteral()),
        };
    }
}
