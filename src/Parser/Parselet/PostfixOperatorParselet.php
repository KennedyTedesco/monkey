<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Parselet;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Ast\Expressions\PostfixExpression;
use MonkeyLang\Parser\Parser;

final readonly class PostfixOperatorParselet implements PostfixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(): Expression
    {
        return new PostfixExpression($this->parser->prevToken(), $this->parser->curToken()->literal());
    }
}
