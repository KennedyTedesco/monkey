<?php

declare(strict_types=1);

namespace MonkeyLang\Ast;

use MonkeyLang\Ast\Statements\Statement;

use MonkeyLang\Support\StringBuilder;

use function count;

final class Program extends Node
{
    /** @var array<Statement> */
    private array $statements = [];

    public function statement(int $index): Statement
    {
        return $this->statements[$index];
    }

    /**
     * @return Statement[]
     */
    public function statements(): array
    {
        return $this->statements;
    }

    public function addStatement(Statement $statement): void
    {
        $this->statements[] = $statement;
    }

    public function count(): int
    {
        return count($this->statements);
    }

    public function toString(): string
    {
        $stringBuilder = StringBuilder::new();

        foreach ($this->statements as $statement) {
            $stringBuilder->append($statement);
        }

        return $stringBuilder->toString();
    }
}
