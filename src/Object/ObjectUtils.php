<?php

declare(strict_types=1);

namespace Monkey\Object;

final class ObjectUtils
{
    public static function isTruthy(InternalObject $object): bool
    {
        if ($object instanceof BooleanObject) {
            return $object->value();
        }

        if ($object instanceof NullObject) {
            return false;
        }

        if ($object instanceof IntegerObject && 0 === $object->value()) {
            return false;
        }

        return true;
    }
}
