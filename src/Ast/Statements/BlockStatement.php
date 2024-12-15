<?php

declare(strict_types=1);

namespace MonkeyLang\Ast\Statements;

namespace MonkeyLang\Ast\Statements;

use MonkeyLang\Support\StringBuilder;
use MonkeyLang\Token\Token;

final class BlockStatement extends Statement
{
    /**
     * @param array<Statement> $statements
     */
    public function __construct(
        public readonly Token $token,
        public readonly array $statements,
    ) {
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
