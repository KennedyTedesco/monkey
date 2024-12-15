<?php

declare(strict_types=1);

namespace MonkeyLang\Object;

use function sprintf;

final readonly class IntegerObject extends MonkeyObject
{
    public function __construct(
        public int $value,
    ) {
    }

    public function type(): MonkeyObjectType
    {
        return MonkeyObjectType::INTEGER;
    }

    public function inspect(): string
    {
        return sprintf('%d', $this->value);
    }

    public function value(): int
    {
        return $this->value;
    }
}
