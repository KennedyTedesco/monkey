<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser\Parselet;

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Ast\Expressions\PostfixExpression;
use MonkeyLang\Lang\Parser\Parser;

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
