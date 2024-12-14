<?php

declare(strict_types=1);

namespace Monkey\Lexer;

use Stringable;

use function strlen;

final readonly class Char implements Stringable
{
    public function __construct(
        public string $char,
    ) {
    }

    public function __toString(): string
    {
        return $this->char;
    }

    public static function empty(): self
    {
        return new self('');
    }

    public static function from(string $char): self
    {
        return new self($char);
    }

    public function isWhitespace(): bool
    {
        return ctype_space($this->char);
    }

    public function isLetter(): bool
    {
        return $this->char === '_' || ctype_alpha($this->char);
    }

    public function isAlphanumeric(): bool
    {
        return $this->char === '_' || ctype_alnum($this->char);
    }

    public function isDigit(): bool
    {
        return ctype_digit($this->char);
    }

    public function is(string $char): bool
    {
        return $char === $this->char;
    }

    public function isSingleChar(): bool
    {
        return strlen($this->char) === 1;
    }

    public function toString(): string
    {
        return $this->char;
    }
}
