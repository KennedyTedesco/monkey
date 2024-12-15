<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator\Builtin;

use MonkeyLang\Object\ArrayObject;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\IntegerObject;
use MonkeyLang\Object\MonkeyObject;
use MonkeyLang\Object\StringObject;

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
