<?php

declare(strict_types=1);

namespace Monkey\Lexer;

final class Char
{
    public function __construct(private string $ch)
    {
    }

    public static function empty(): self
    {
        return new self('');
    }

    public static function from(string $ch): self
    {
        return new self($ch);
    }

    public function isWhitespace(): bool
    {
        return ctype_space($this->ch);
    }

    public function isLetter(): bool
    {
        return '_' === $this->ch || ctype_alpha($this->ch);
    }

    public function isAlphanumeric(): bool
    {
        return '_' === $this->ch || ctype_alnum($this->ch);
    }

    public function isDigit(): bool
    {
        return ctype_digit($this->ch);
    }

    public function is(string $ch): bool
    {
        return $ch === $this->ch;
    }

    public function toScalar(): string
    {
        return $this->ch;
    }
}
