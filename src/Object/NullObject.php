<?php

declare(strict_types=1);

namespace Monkey\Object;

final class NullObject extends MonkeyObject
{
    private static self $null;

    public function value(): void
    {
    }

    public function type(): string
    {
        return self::NULL_OBJ;
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
