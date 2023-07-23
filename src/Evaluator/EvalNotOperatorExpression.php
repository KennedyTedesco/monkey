<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\BooleanObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;

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
