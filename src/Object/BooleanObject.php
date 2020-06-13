<?php

declare(strict_types=1);

namespace Monkey\Object;

final class BooleanObject implements InternalObject
{
    private bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function type(): string
    {
        return self::BOOLEAN_OBJ;
    }

    public function inspect(): string
    {
        return $this->value ? 'true' : 'false';
    }

    public static function true(): self
    {
        static $true;
        return $true ??= new self(true);
    }

    public static function false(): self
    {
        static $false;
        return $false ??= new self(false);
    }
}
