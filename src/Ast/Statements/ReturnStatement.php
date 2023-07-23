<?php

declare(strict_types=1);

namespace Monkey\Ast\Statements;

namespace Monkey\Ast\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Support\StringBuilder;
use Monkey\Token\Token;

final class ReturnStatement extends Statement
{
    public function __construct(
        public readonly Token $token,
        public readonly Expression $value,
    ) {
    }

    public function toString(): string
    {
        return StringBuilder::new()
            ->append($this->token->literal)
            ->appendSpace()
            ->append($this->value)
            ->append(';')
            ->toString();
    }
}
