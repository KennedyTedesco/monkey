<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\PostfixExpression;
use Monkey\Parser\Parser;

final class PostfixOperatorParselet implements PostfixParselet
{
    public function __construct(private Parser $parser)
    {
    }

    public function parse(): Expression
    {
        return new PostfixExpression($this->parser->prevToken, $this->parser->curToken->literal());
    }
}
