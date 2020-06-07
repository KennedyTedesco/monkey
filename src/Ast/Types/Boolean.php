<?php

declare(strict_types=1);

namespace Monkey\Ast\Types;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class Boolean extends Expression
{
    private bool $value;

    public function __construct(Token $token, bool $value)
    {
        $this->token = $token;
        $this->value = $value;
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->tokenLiteral();
    }
}
