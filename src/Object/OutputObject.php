<?php

declare(strict_types=1);

namespace Monkey\Object;

final class OutputObject extends MonkeyObject
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function value()
    {
        return $this->value;
    }

    public function type(): int
    {
        return self::MO_OUTPUT;
    }

    public function typeLiteral(): string
    {
        return 'OUTPUT';
    }

    public function inspect(): string
    {
        return $this->value->inspect();
    }
}
