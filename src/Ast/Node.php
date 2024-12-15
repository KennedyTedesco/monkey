<?php

declare(strict_types=1);

namespace MonkeyLang\Ast;

use Stringable;

abstract class Node implements Stringable
{
    public function __toString(): string
    {
        return $this->toString();
    }

    abstract public function toString(): string;
}
