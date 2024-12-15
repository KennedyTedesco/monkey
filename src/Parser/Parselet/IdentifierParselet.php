<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Parselet;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Ast\Expressions\IdentifierExpression;
use MonkeyLang\Parser\Parser;

final readonly class IdentifierParselet implements PrefixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(): Expression
    {
        return new IdentifierExpression($this->parser->curToken(), $this->parser->curToken()->literal());
    }
}
