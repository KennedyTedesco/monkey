<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator\Builtin;

use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Object\NullObject;

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
