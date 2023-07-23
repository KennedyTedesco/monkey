<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;

use function count;

final readonly class EvalPutsFunction extends EvalBuiltinFunction
{
    public function __invoke(MonkeyObject ...$monkeyObject): MonkeyObject
    {
        if ($monkeyObject === []) {
            return ErrorObject::wrongNumberOfArguments(count($monkeyObject), 1);
        }

        echo implode('', array_map(function (MonkeyObject $monkeyObject): string {
            return $monkeyObject->inspect();
        }, $monkeyObject));

        return NullObject::instance();
    }
}
