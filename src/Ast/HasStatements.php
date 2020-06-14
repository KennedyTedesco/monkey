<?php

declare(strict_types=1);

namespace Monkey\Ast;

use Monkey\Ast\Statements\Statement;

interface HasStatements
{
    /**
     * @return array<Statement>
     */
    public function statements(): array;
}
