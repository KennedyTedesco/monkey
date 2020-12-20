<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final class EvalUnaryExpression
{
    public function __invoke(string $operator, MonkeyObject $right): MonkeyObject
    {
        return match (true) {
            $right instanceof ErrorObject => $right,
            '!' === $operator => (new EvalNotOperatorExpression())($right),
            '-' === $operator => (new EvalMinusUnaryOperatorExpression())($right),
            default => ErrorObject::unknownOperator($operator, $right->typeLiteral()),
        };
    }
}
