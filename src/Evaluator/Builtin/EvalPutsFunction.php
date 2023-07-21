<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;

use function count;

final class EvalPutsFunction
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
