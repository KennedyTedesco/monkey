<?php

declare(strict_types=1);

namespace MonkeyLang\Object;

final readonly class ReturnValueObject extends MonkeyObject
{
    public function __construct(
        public MonkeyObject $value,
    ) {
    }

    public function type(): MonkeyObjectType
    {
        return MonkeyObjectType::RETURN_VALUE;
    }

    public function inspect(): string
    {
        return $this->value->inspect();
    }

    public function value(): MonkeyObject
    {
        return $this->value;
    }
}
