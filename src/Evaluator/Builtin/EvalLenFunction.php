<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\StringObject;

final class EvalLenFunction
{
    public function __invoke(MonkeyObject ...$arguments): MonkeyObject
    {
        if (1 !== \count($arguments)) {
            return ErrorObject::wrongNumberOfArguments(\count($arguments), 1);
        }

        $object = $arguments[0];

        switch (true) {
            case $object instanceof StringObject:
                return new IntegerObject(\mb_strlen($object->value()));
            case $object instanceof ArrayObject:
                return new IntegerObject(\count($object->value()));
            default:
                return ErrorObject::invalidArgument('len', $object->typeLiteral());
        }
    }
}
