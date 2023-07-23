<?php

declare(strict_types=1);

namespace Monkey\Ast\Types;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class FloatLiteral extends Expression
{
    public function __construct(
        public readonly Token $token,
        public readonly float $value,
    ) {
    }

    public function toString(): string
    {
        return $this->token->literal;
    }
}
