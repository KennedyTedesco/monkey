<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Node;
use Monkey\Parser\Parser;

abstract class Parselet
{
    protected Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    abstract public function parse(): Node;
}
