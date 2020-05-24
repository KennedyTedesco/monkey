<?php

declare(strict_types=1);

namespace Monkey\Token;

/**
 * @psalm-immutable
 */
final class Token
{
    private Type $type;
    private Literal $literal;

    public function __construct(Type $type, Literal $literal)
    {
        $this->type = $type;
        $this->literal = $literal;
    }

    public function type(): string
    {
        return $this->type->value;
    }

    public function literal(): string
    {
        return $this->literal->value;
    }
}
