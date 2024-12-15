<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator\Builtin;

use MonkeyLang\Lang\Object\ArrayObject;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\IntegerObject;
use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Object\StringObject;

use function count;

final readonly class EvalLenFunction extends EvalBuiltinFunction
{
    public function __invoke(MonkeyObject ...$monkeyObject): MonkeyObject
    {
        if (count($monkeyObject) !== 1) {
            return ErrorObject::wrongNumberOfArguments(count($monkeyObject), 1);
        }

        $object = $monkeyObject[0];

        return match (true) {
            $object instanceof StringObject => new IntegerObject($object->count()),
            $object instanceof ArrayObject => new IntegerObject($object->count()),
            default => ErrorObject::invalidArgument('len()', $object->typeLiteral()),
        };
    }
}
