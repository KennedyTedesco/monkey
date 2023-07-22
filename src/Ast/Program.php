<?php

declare(strict_types=1);

namespace Monkey\Ast;

use Monkey\Ast\Statements\Statement;

use function count;

final class Program extends Node
{
    /** @var array<Statement> */
    private array $statements = [];

    public function statement(int $index): Node
    {
        return $this->statements[$index];
    }

    /**
     * @return array<Statement>
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

    public function tokenLiteral(): string
    {
        if ($this->count() <= 0) {
            return '';
        }

        return $this->statement(0)->tokenLiteral();
    }

    public function toString(): string
    {
        $out = '';
        foreach ($this->statements as $statement) {
            $out .= $statement->toString();
        }

        return $out;
    }
}
