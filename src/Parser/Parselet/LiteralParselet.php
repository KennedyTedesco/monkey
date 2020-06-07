<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Types\IntegerLiteral;
use Monkey\Parser\Parser;

final class LiteralParselet implements PrefixParselet
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(): Expression
    {
        return new IntegerLiteral($this->parser->curToken, (int) $this->parser->curToken->literal());
    }
}
