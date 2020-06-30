<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\PostfixExpression;
use Monkey\Parser\Parser;

final class PostfixOperatorParselet implements PostfixParselet
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(): Expression
    {
        return new PostfixExpression($this->parser->prevToken, $this->parser->curToken->literal());
    }
}
