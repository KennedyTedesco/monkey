<?php

declare(strict_types=1);

namespace MonkeyLang\Object;

final readonly class BooleanObject extends MonkeyObject
{
    public function __construct(
        public bool $value,
    ) {
    }

    public static function true(): self
    {
        return new self(true);
    }

    public static function false(): self
    {
        return new self(false);
    }

    public static function from(bool $value): self
    {
        if ($value) {
            return self::true();
        }

        return self::false();
    }

    public function type(): MonkeyObjectType
    {
        return MonkeyObjectType::BOOL;
    }

    public function inspect(): string
    {
        return $this->value ? 'true' : 'false';
    }

    public function value(): bool
    {
        return $this->value;
    }
}
