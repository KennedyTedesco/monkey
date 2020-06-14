<?php

declare(strict_types=1);

namespace Monkey\Object;

final class BooleanObject implements InternalObject
{
    private bool $value;
    private static self $true;
    private static self $false;

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

    public static function from(bool $value): self
    {
        if ($value) {
            return self::$true ??= new self(true);
        }

        return self::$false ??= new self(false);
    }
}
