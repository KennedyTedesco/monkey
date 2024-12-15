<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Object\ArrayObject;
use MonkeyLang\Lang\Object\BooleanObject;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\FloatObject;
use MonkeyLang\Lang\Object\IntegerObject;
use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Object\StringObject;

final class EvalBinaryExpression
{
    public function __invoke(
        string $operator,
        MonkeyObject $left,
        MonkeyObject $right,
    ): MonkeyObject {
        return match (true) {
            $left instanceof ErrorObject => $left,

            $right instanceof ErrorObject => $right,

            $left->type() !== $right->type() =>
                ErrorObject::typeMismatch($left->typeLiteral(), $operator, $right->typeLiteral()),

            $left instanceof IntegerObject && $right instanceof IntegerObject,
            $left instanceof FloatObject && $right instanceof FloatObject =>
                new EvalNumericBinaryExpression()($operator, $left, $right),

            $left instanceof StringObject && $right instanceof StringObject =>
                new EvalStringBinaryExpression()($operator, $left, $right),

            $left instanceof ArrayObject && $right instanceof ArrayObject =>
                new EvalArrayBinaryExpression()($operator, $left, $right),

            $operator === '&&' =>
                BooleanObject::from($left->value() && $right->value()),

            $operator === '||' =>
                BooleanObject::from($left->value() || $right->value()),

            $operator === '==' =>
                BooleanObject::from($left->value() === $right->value()),

            $operator === '!=' =>
                BooleanObject::from($left->value() !== $right->value()),

            default => ErrorObject::unknownOperator($left->typeLiteral(), $operator, $right->typeLiteral()),
        };
    }
}
