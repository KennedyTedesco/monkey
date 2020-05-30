<?php

declare(strict_types=1);

namespace Monkey\Ast;

interface Expression extends Node
{
    public function expressionNode();
}
