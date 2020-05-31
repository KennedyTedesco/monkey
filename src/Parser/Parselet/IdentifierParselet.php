<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Identifier;
use Monkey\Ast\Node;

final class IdentifierParselet extends Parselet
{
    public function parse(): Node
    {
        return new Identifier($this->parser->curToken, $this->parser->curToken->literal);
    }
}
