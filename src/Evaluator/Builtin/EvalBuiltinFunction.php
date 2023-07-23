<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use Monkey\Evaluator\Evaluator;
use Monkey\Object\MonkeyObject;

abstract readonly class EvalBuiltinFunction
{
    public function __construct(
        public Evaluator $evaluator,
    ) {
    }

    abstract public function __invoke(MonkeyObject ...$monkeyObject): MonkeyObject;
}
