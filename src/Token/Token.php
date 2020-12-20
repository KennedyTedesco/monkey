<?php

declare(strict_types=1);

namespace Monkey\Token;

final class Token
{
    public function __construct(private int $type, private string $literal)
    {
    }

    public static function from(int $type, string $literal): self
    {
        return new self($type, $literal);
    }

    public function is(int $type): bool
    {
        return $type === $this->type;
    }

    public function type(): int
    {
        return $this->type;
    }

    public function literal(): string
    {
        return $this->literal;
    }
}
