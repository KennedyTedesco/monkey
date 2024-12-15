<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator\Builtin;

use MonkeyLang\Object\ArrayObject;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\MonkeyObject;
use MonkeyLang\Object\NullObject;

use function count;

final readonly class EvalLastFunction extends EvalBuiltinFunction
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
