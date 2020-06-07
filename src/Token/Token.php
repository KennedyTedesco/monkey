<?php

declare(strict_types=1);

namespace Monkey\Token;

final class Token
{
    private int $type;
    private string $literal;

    public function __construct(int $type, string $literal)
    {
        $this->type = $type;
        $this->literal = $literal;
    }

    public static function from(int $type, string $literal): self
    {
        return new self($type, $literal);
    }

    public function is(int ...$types): bool
    {
        foreach ($types as $type) {
            if ($type === $this->type) {
                return true;
            }
        }
        return false;
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
