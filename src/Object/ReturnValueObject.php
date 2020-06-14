<?php

declare(strict_types=1);

namespace Monkey\Object;

final class ReturnValueObject implements InternalObject
{
    private InternalObject $value;

    public function __construct(InternalObject $value)
    {
        $this->value = $value;
    }

    public function value(): InternalObject
    {
        return $this->value;
    }

    public function type(): string
    {
        return self::RETURN_VALUE_OBJ;
    }

    public function inspect(): string
    {
        return $this->value->inspect();
    }
}
