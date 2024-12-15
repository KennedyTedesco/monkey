<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Object\ArrayObject;
use MonkeyLang\Object\BooleanObject;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\FloatObject;
use MonkeyLang\Object\IntegerObject;
use MonkeyLang\Object\MonkeyObject;
use MonkeyLang\Object\StringObject;

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
