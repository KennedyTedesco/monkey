<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Object\BooleanObject;
use MonkeyLang\Object\MonkeyObject;
use MonkeyLang\Object\NullObject;

final class EvalNotOperatorExpression
{
    public function __invoke(MonkeyObject $monkeyObject): MonkeyObject
    {
        return match (true) {
            $monkeyObject instanceof BooleanObject => BooleanObject::from(!$monkeyObject->value),
            $monkeyObject instanceof NullObject => BooleanObject::true(),
            default => BooleanObject::false(),
        };
    }
}
