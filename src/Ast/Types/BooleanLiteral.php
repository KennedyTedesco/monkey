<?php

declare(strict_types=1);

namespace Monkey\Ast\Types;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class BooleanLiteral extends Expression
{
    public function __construct(
        Token $token,
        public readonly bool $value,
    ) {
        $this->token = $token;
    }

    public function toString(): string
    {
        return $this->tokenLiteral();
    }
}
