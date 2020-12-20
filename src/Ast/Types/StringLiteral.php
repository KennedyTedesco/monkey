<?php

declare(strict_types=1);

namespace Monkey\Ast\Types;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class StringLiteral extends Expression
{
    public function __construct(Token $token, private string $value)
    {
        $this->token = $token;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->tokenLiteral();
    }
}
