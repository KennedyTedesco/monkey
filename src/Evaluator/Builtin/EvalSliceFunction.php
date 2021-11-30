<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\StringObject;

final class EvalSliceFunction
{
    public function __invoke(MonkeyObject ...$arguments): MonkeyObject
    {
        $count = \count($arguments);
        if ($count < 2 || $count > 3) {
            return ErrorObject::wrongNumberOfArguments($count, 2);
        }

        $offset = $arguments[1];
        if (!$offset instanceof IntegerObject) {
            return ErrorObject::invalidArgument('slice(offset)', $offset->typeLiteral());
        }

        $length = $arguments[2] ?? null;
        if (null !== $length && !$length instanceof IntegerObject) {
            return ErrorObject::invalidArgument('slice(..., length)', $length->typeLiteral());
        }

        $object = $arguments[0];
        if ($object instanceof ArrayObject) {
            return new ArrayObject(
                \array_slice($object->value(), $offset->value(), (null !== $length ? $length->value() : null))
            );
        }

        if ($object instanceof StringObject) {
            $params = [
                $object->value(),
                $offset->value(),
            ];

            if (null !== $length) {
                $params[] = $length->value();
            }

            return new StringObject(mb_substr(...$params));
        }

        return ErrorObject::invalidArgument('slice()', $object->typeLiteral());
    }
}
