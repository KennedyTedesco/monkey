<?php

declare(strict_types=1);

namespace Monkey\Lexer;

/**
 * @psalm-immutable
 */
final class Char
{
    private string $ch;

    public function __construct(string $ch)
    {
        $this->ch = $ch;
    }

    public static function from(string $ch): self
    {
        return new self($ch);
    }

    public function isWhitespace(): bool
    {
        return \ctype_space($this->ch);
    }

    public function isLetter(): bool
    {
        return '_' === $this->ch || \ctype_alpha($this->ch);
    }

    public function isDigit(): bool
    {
        return \ctype_digit($this->ch);
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
