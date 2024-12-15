<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator\Builtin;

use MonkeyLang\Lang\Evaluator\Evaluator;
use MonkeyLang\Lang\Object\MonkeyObject;

abstract readonly class EvalBuiltinFunction
{
    public function __construct(
        public Evaluator $evaluator,
    ) {
    }

    abstract public function __invoke(MonkeyObject ...$monkeyObject): MonkeyObject;
}
