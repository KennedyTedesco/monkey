<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator\Builtin;

use MonkeyLang\Lang\Object\ArrayObject;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Object\NullObject;

use function count;

final readonly class EvalFirstFunction extends EvalBuiltinFunction
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

        $elements = $object->value;

        if ($elements !== []) {
            return reset($elements);
        }

        return NullObject::instance();
    }
}
