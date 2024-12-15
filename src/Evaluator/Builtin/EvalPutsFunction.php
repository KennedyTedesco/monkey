<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator\Builtin;

use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\MonkeyObject;
use MonkeyLang\Object\NullObject;

use function count;

final readonly class EvalPutsFunction extends EvalBuiltinFunction
{
    public function __invoke(MonkeyObject ...$monkeyObject): MonkeyObject
    {
        if ($monkeyObject === []) {
            return ErrorObject::wrongNumberOfArguments(count($monkeyObject), 1);
        }

        echo implode('', array_map(fn (MonkeyObject $monkeyObject): string => $monkeyObject->inspect(), $monkeyObject));

        return NullObject::instance();
    }
}
