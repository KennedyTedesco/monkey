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
        if ($right instanceof BooleanObject) {
            return BooleanObject::from(!$right->value());
        }

        if ($right instanceof NullObject) {
            return BooleanObject::from(true);
        }

        return BooleanObject::from(false);
    }
}
