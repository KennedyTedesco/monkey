<?php

declare(strict_types=1);

namespace Monkey\Object;

final class BooleanObject extends MonkeyObject
{
    private static self $true;
    private static self $false;

    public function __construct(private bool $value)
    {
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function type(): int
    {
        return self::MO_BOOL;
    }

    public function typeLiteral(): string
    {
        return 'BOOL';
    }

    public function inspect(): string
    {
        return $this->value ? 'true' : 'false';
    }

    public static function true(): self
    {
        return self::$true ??= new self(true);
    }

    public static function false(): self
    {
        return self::$false ??= new self(false);
    }

    public static function from(bool $value): self
    {
        if (true === $value) {
            return self::true();
        }

        return self::false();
    }
}
