<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;

final class EvalPutsFunction
{
    public function __invoke(MonkeyObject ...$arguments): MonkeyObject
    {
        if (0 === \count($arguments)) {
            return ErrorObject::wrongNumberOfArguments(\count($arguments), 1);
        }

        echo \implode('', \array_map(fn (MonkeyObject $argument) => $argument->inspect(), $arguments));

        return NullObject::instance();
    }
}
