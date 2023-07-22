<?php

declare(strict_types=1);

namespace Monkey\Object;

final readonly class ReturnValueObject extends MonkeyObject
{
    public function __construct(
        public MonkeyObject $value,
    ) {
    }

    public function type(): int
    {
        return self::MO_RETURN_VALUE;
    }

    public function typeLiteral(): string
    {
        return 'RETURN_VALUE';
    }

    public function inspect(): string
    {
        return $this->value->inspect();
    }
}
