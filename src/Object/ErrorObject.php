<?php

declare(strict_types=1);

namespace Monkey\Object;

final class ErrorObject implements InternalObject
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function type(): string
    {
        return self::BOOLEAN_OBJ;
    }

    public function inspect(): string
    {
        return "ERROR: {$this->value}";
    }

    public static function unknownOperator(string ...$args): self
    {
        return new self('unknown operator: '.\implode(' ', $args));
    }

    public static function typeMismatch(string ...$args): self
    {
        return new self('type mismatch: '.\implode(' ', $args));
    }
}
