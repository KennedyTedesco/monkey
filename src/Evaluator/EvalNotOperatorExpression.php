<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\BooleanObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;

final class EvalNotOperatorExpression
{
    public function __invoke(MonkeyObject $right): MonkeyObject
    {
        return match (true) {
            $right instanceof BooleanObject => BooleanObject::from(!$right->value()),
            $right instanceof NullObject => BooleanObject::true(),
            default => BooleanObject::false(),
        };
    }
}
