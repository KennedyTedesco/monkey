<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expression;
use Monkey\Ast\Identifier;

final class IdentifierParselet extends Parselet
{
    public function parse(): Expression
    {
        return new Identifier($this->parser->curToken, $this->parser->curToken->literal);
    }
}
