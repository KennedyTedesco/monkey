<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\BooleanObject;
use Monkey\Object\InternalObject;
use Monkey\Object\NullObject;

final class EvalBangOperatorExpression
{
    public function __invoke(InternalObject $right): InternalObject
    {
        switch (true) {
            case $right instanceof BooleanObject:
                return BooleanObject::from(!$right->value());

            case $right instanceof NullObject:
                return BooleanObject::from(true);

            default:
                return BooleanObject::from(false);
        }
    }
}
