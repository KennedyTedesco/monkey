<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;

use function count;

final class EvalFirstFunction
{
    public function __invoke(MonkeyObject ...$monkeyObject): MonkeyObject
    {
        if (count($monkeyObject) !== 1) {
            return ErrorObject::wrongNumberOfArguments(count($monkeyObject), 1);
        }

        $object = $monkeyObject[0];

        if (!$object instanceof ArrayObject) {
            return ErrorObject::invalidArgument('first()', $object->typeLiteral());
        }

        $elements = $object->value();

        if ($elements !== []) {
            return reset($elements);
        }

        return NullObject::instance();
    }
}
