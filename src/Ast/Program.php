<?php

declare(strict_types=1);

namespace Monkey\Ast;

use Monkey\Ast\Statements\Statement;

use Monkey\Support\StringBuilder;

use function count;

final class Program extends Node
{
    public array $statements = [];

    public function statement(int $index): Statement
    {
        return $this->statements[$index];
    }

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

    public function tokenLiteral(): string
    {
        if ($this->count() <= 0) {
            return '';
        }

        return $this->statement(0)->tokenLiteral();
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
