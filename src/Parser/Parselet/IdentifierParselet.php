<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Parser\Parser;

final readonly class IdentifierParselet implements PrefixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(): Expression
    {
        return new IdentifierExpression($this->parser->curToken, $this->parser->curToken->literal());
    }
}
