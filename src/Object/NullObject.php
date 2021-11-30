<?php

declare(strict_types=1);

namespace Monkey\Object;

final class NullObject extends MonkeyObject
{
    private static self $null;

    /**
     * @return null
     */
    public function value()
    {
        return null;
    }

    public function type(): int
    {
        return self::MO_NULL;
    }

    public function typeLiteral(): string
    {
        return 'NULL';
    }

    public function inspect(): string
    {
        return 'null';
    }

    public static function instance(): self
    {
        return self::$null ??= new self();
    }
}
