<?php

declare(strict_types=1);

namespace Monkey\Ast\Statements;

namespace Monkey\Ast\Statements;

use Monkey\Support\StringBuilder;
use Monkey\Token\Token;

final class BlockStatement extends Statement
{
    public function __construct(
        Token $token,
        public readonly array $statements,
    ) {
        $this->token = $token;
    }

    public function toString(): string
    {
        $builder = StringBuilder::new();

        foreach ($this->statements as $statement) {
            $builder->append($statement);
        }

        return $builder->toString();
    }
}
