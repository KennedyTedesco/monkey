<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use function count;
use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;

final class EvalLastFunction
{
    public function __invoke(MonkeyObject ...$arguments): MonkeyObject
    {
        if (1 !== count($arguments)) {
            return ErrorObject::wrongNumberOfArguments(count($arguments), 1);
        }

        $object = $arguments[0];
        if (!$object instanceof ArrayObject) {
            return ErrorObject::invalidArgument('last()', $object->typeLiteral());
        }

        $elements = $object->value();
        if ([] !== $elements) {
            return end($elements);
        }

        return NullObject::instance();
    }
}
