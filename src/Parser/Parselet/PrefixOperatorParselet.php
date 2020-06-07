<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\PrefixExpression;
use Monkey\Parser\Parser;
use Monkey\Parser\Precedence;

final class PrefixOperatorParselet implements PrefixParselet
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(): Expression
    {
        $token = $this->parser->curToken;

        $this->parser->nextToken();

        /** @var Expression $right */
        $right = $this->parser->parseExpression(Precedence::PREFIX);

        return new PrefixExpression($token, $token->literal, $right);
    }
}
