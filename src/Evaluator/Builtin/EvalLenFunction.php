<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\StringObject;

use function count;

final class EvalLenFunction
{
    public function __invoke(MonkeyObject ...$monkeyObject): MonkeyObject
    {
        if (count($monkeyObject) !== 1) {
            return ErrorObject::wrongNumberOfArguments(count($monkeyObject), 1);
        }

        $object = $monkeyObject[0];

        return match (true) {
            $object instanceof StringObject => new IntegerObject(mb_strlen($object->value)),
            $object instanceof ArrayObject => new IntegerObject(is_countable($object->value) ? count($object->value) : 0),
            default => ErrorObject::invalidArgument('len()', $object->typeLiteral()),
        };
    }
}
