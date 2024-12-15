<?php

declare(strict_types=1);

namespace MonkeyLang\Token;

final readonly class Token
{
    public function __construct(
        public TokenType $type,
        public string $literal,
    ) {
    }

    public static function from(TokenType $type, string $literal): self
    {
        return new self($type, $literal);
    }

    public function is(TokenType $type): bool
    {
        return $type === $this->type;
    }

    public function type(): TokenType
    {
        return $this->type;
    }

    public function literal(): string
    {
        return $this->literal;
    }
}
