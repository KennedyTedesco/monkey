<?php

declare(strict_types=1);

namespace Monkey\Ast\Types;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class FloatLiteral extends Expression
{
    public function __construct(Token $token, private float $value)
    {
        $this->token = $token;
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
