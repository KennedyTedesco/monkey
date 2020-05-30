<?php

declare(strict_types=1);

namespace Monkey\Token;

/**
 * @psalm-immutable
 */
final class Token
{
    public int $type;
    public string $literal;

    public function __construct(int $type, string $literal)
    {
        $this->type = $type;
        $this->literal = $literal;
    }
}
