<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;

use function count;

final class EvalLastFunction
{
    public function __invoke(MonkeyObject ...$monkeyObject): MonkeyObject
    {
        if (count($monkeyObject) !== 1) {
            return ErrorObject::wrongNumberOfArguments(count($monkeyObject), 1);
        }

        $object = $monkeyObject[0];

        if (!$object instanceof ArrayObject) {
            return ErrorObject::invalidArgument('last()', $object->typeLiteral());
        }

        $elements = $object->value;

        if ($elements !== []) {
            return end($elements);
        }

        return NullObject::instance();
    }
}
