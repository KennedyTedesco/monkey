<?php

declare(strict_types=1);

namespace Monkey\Object;

final class ReturnValueObject extends MonkeyObject
{
    private MonkeyObject $value;

    public function __construct(MonkeyObject $value)
    {
        $this->value = $value;
    }

    public function value(): MonkeyObject
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
