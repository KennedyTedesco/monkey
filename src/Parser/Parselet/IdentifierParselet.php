<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\Identifier;
use Monkey\Parser\Parser;

final class IdentifierParselet implements Parselet
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(): Expression
    {
        return new Identifier($this->parser->curToken, $this->parser->curToken->literal);
    }
}
