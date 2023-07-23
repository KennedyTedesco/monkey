<?php

declare(strict_types=1);

namespace Monkey\Lexer;

use Stringable;

use function strlen;

final readonly class Char implements Stringable
{
    public function __construct(
        public string $ch,
    ) {
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
        return $this->ch === '_' || ctype_alpha($this->ch);
    }
    public function isAlphanumeric(): bool
    {
        return $this->ch === '_' || ctype_alnum($this->ch);
    }
    public function isDigit(): bool
    {
        return ctype_digit($this->ch);
    }
    public function is(string $ch): bool
    {
        return $ch === $this->ch;
    }

    public function isSingleChar(): bool
    {
        return strlen($this->ch) === 1;
    }

    public function toString(): string
    {
        return $this->ch;
    }
    public function __toString(): string
    {
        return $this->ch;
    }
}
