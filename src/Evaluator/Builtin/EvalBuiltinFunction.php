<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator\Builtin;

use MonkeyLang\Evaluator\Evaluator;
use MonkeyLang\Object\MonkeyObject;

abstract readonly class EvalBuiltinFunction
{
    public function __construct(
        public Evaluator $evaluator,
    ) {
    }

    abstract public function __invoke(MonkeyObject ...$monkeyObject): MonkeyObject;
}
