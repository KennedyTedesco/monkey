<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\InternalObject;
use Monkey\Object\StringObject;

final class EvalLenFunction
{
    public function __invoke(InternalObject ...$arguments): InternalObject
    {
        if (1 !== \count($arguments)) {
            return ErrorObject::wrongNumberOfArguments(\count($arguments), 1);
        }

        /** @var InternalObject $object */
        $object = $arguments[0];

        if ($object instanceof StringObject) {
            return new IntegerObject(\mb_strlen($object->value()));
        }

        return ErrorObject::invalidArgument('len', $object->type());
    }
}
