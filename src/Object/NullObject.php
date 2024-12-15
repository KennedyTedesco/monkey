<?php

declare(strict_types=1);

namespace Monkey\Object;

final readonly class NullObject extends MonkeyObject
{
    public function __construct(
        public null $value,
    ) {
    }

    public static function instance(): self
    {
        return new self(null);
    }

    public function type(): MonkeyObjectType
    {
        return MonkeyObjectType::NULL;
    }

    public function inspect(): string
    {
        return 'null';
    }

    public function value(): null
    {
        return $this->value;
    }
}
