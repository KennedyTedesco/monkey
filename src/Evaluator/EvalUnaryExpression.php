<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\InternalObject;

final class EvalUnaryExpression
{
    public function __invoke(string $operator, InternalObject $right): ?InternalObject
    {
        switch ($operator) {
            case '!':
                return (new EvalBangOperatorExpression())($right);
            default:
                return null;
        }
    }
}
