<?php

declare(strict_types=1);

namespace Monkey\Object;

final class StringObject extends MonkeyObject
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
        return self::STRING_OBJ;
    }

    public function inspect(): string
    {
        return \sprintf('%s', $this->value);
    }
}
