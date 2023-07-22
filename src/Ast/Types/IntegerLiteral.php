<?php

declare(strict_types=1);

namespace Monkey\Ast\Types;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class IntegerLiteral extends Expression
{
    public function __construct(
        Token $token,
        public readonly int $value,
    ) {
        $this->token = $token;
    }

    public function toString(): string
    {
        return $this->tokenLiteral();
    }
}
