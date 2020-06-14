<?php

declare(strict_types=1);

namespace Monkey\Ast;

namespace Monkey\Ast\Statements;

use Monkey\Ast\HasStatements;
use Monkey\Token\Token;

final class BlockStatement extends Statement implements HasStatements
{
    /** @var array<Statement> */
    private array $statements;

    public function __construct(Token $token, array $statements)
    {
        $this->token = $token;
        $this->statements = $statements;
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
