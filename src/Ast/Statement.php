<?php

declare(strict_types=1);

namespace Monkey\Ast;

interface Statement extends Node
{
    public function statementNode();
}
