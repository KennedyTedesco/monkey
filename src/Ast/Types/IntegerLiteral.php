<?php

declare(strict_types=1);

namespace Monkey\Ast\Types;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class IntegerLiteral extends Expression
{
    public function __construct(
        public readonly Token $token,
        public readonly int $value,
    ) {
    }

    public function toString(): string
    {
        return $this->token->literal;
    }
}
