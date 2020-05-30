<?php

declare(strict_types=1);

namespace Monkey\Ast;

final class Program implements Node
{
    /**
     * @var array<Statement>
     */
    private array $statements = [];

    public function tokenLiteral(): string
    {
        if (0 === \count($this->statements)) {
            return '';
        }

        return $this->statements[0]->tokenLiteral();
    }
}
