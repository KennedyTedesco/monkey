<?php

declare(strict_types=1);

namespace Monkey\Token;

/**
 * @psalm-immutable
 */
final class Literal
{
    public string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
