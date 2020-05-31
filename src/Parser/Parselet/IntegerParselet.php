<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Node;
use Monkey\Ast\Types\Integer;

final class IntegerParselet extends Parselet
{
    public function parse(): Node
    {
        return new Integer($this->parser->curToken, (int) $this->parser->curToken->literal);
    }
}
