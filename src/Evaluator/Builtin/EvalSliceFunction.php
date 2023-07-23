<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\StringObject;

use function array_slice;
use function count;

final readonly class EvalSliceFunction extends EvalBuiltinFunction
{
    public function __invoke(MonkeyObject ...$monkeyObject): MonkeyObject
    {
        $count = count($monkeyObject);

        if ($count < 2) {
            return ErrorObject::wrongNumberOfArguments($count, 2);
        }

        if ($count > 3) {
            return ErrorObject::wrongNumberOfArguments($count, 2);
        }

        $offset = $monkeyObject[1];

        if (!$offset instanceof IntegerObject) {
            return ErrorObject::invalidArgument('slice(offset)', $offset->typeLiteral());
        }

        $length = $monkeyObject[2] ?? null;

        if ($length instanceof MonkeyObject && !$length instanceof IntegerObject) {
            return ErrorObject::invalidArgument('slice(..., length)', $length->typeLiteral());
        }

        $object = $monkeyObject[0];

        if ($object instanceof ArrayObject) {
            return new ArrayObject(
                array_slice($object->value, $offset->value, $length instanceof IntegerObject ? $length->value : null),
            );
        }

        if ($object instanceof StringObject) {
            $params = [
                $object->value,
                $offset->value,
            ];

            if ($length instanceof IntegerObject) {
                $params[] = $length->value;
            }

            return new StringObject(mb_substr(...$params));
        }

        return ErrorObject::invalidArgument('slice()', $object->typeLiteral());
    }
}
