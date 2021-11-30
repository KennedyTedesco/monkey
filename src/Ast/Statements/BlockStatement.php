<?php

declare(strict_types=1);

namespace Monkey\Ast;

use Monkey\Ast\Statements\Statement;

namespace Monkey\Ast\Statements;

use Monkey\Token\Token;

final class BlockStatement extends Statement
{
    public function __construct(Token $token, /* @var array<Statement> */
    private array $statements)
    {
        $this->token = $token;
    }

    public function statements(): array
    {
        return $this->statements;
    }

    public function toString(): string
    {
        $out = '';
        /** @var Statement $statement */
        foreach ($this->statements as $statement) {
            $out .= $statement->toString();
        }

        return $out;
    }
}
