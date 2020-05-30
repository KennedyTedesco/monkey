<?php

declare(strict_types=1);

namespace Monkey\Ast;

interface Node
{
    public function tokenLiteral(): string;

    public function toString(): string;
}
