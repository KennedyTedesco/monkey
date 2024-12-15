<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Object\BooleanObject;
use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Object\NullObject;

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
