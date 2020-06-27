<?php

declare(strict_types=1);

namespace Monkey\Object;

final class ObjectUtils
{
    public static function isTruthy(MonkeyObject $object): bool
    {
        switch (true) {
            case $object->isBool():
                return $object->value();
            case $object instanceof NullObject:
            case $object instanceof IntegerObject && 0 === $object->value():
            case $object instanceof StringObject && '' === $object->value():
            case $object instanceof FloatObject && 0.0 === $object->value():
                return false;
            default:
                return true;
        }
    }
}
