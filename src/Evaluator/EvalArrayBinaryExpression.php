<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final class EvalArrayBinaryExpression
{
    public function __invoke(
        string $operator,
        ArrayObject $left,
        ArrayObject $right
    ): MonkeyObject {
        switch ($operator) {
            case '+':
                return new ArrayObject(\array_merge($left->value(), $right->value()));
            default:
                return ErrorObject::unknownOperator($left->typeLiteral(), $operator, $right->typeLiteral());
        }
    }
}
