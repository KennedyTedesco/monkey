<?php

declare(strict_types=1);

namespace Monkey\Ast\Types;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class FloatLiteral extends Expression
{
    private float $value;

    public function __construct(Token $token, float $value)
    {
        $this->token = $token;
        $this->value = $value;
    }

    public function value(): float
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->tokenLiteral();
    }
}
