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
        switch (true) {
            case $right instanceof BooleanObject:
                return BooleanObject::from(!$right->value());

            case $right instanceof NullObject:
                return BooleanObject::true();

            default:
                return BooleanObject::false();
        }
    }
}
