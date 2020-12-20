<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\UnaryExpression;
use Monkey\Parser\Parser;
use Monkey\Parser\Precedence;

final class UnaryOperatorParselet implements PrefixParselet
{
    public function __construct(private Parser $parser)
    {
    }

    public function parse(): Expression
    {
        $token = $this->parser->curToken;

        $this->parser->nextToken();

        /** @var Expression $right */
        $right = $this->parser->parseExpression(Precedence::PREFIX);

        return new UnaryExpression($token, $token->literal(), $right);
    }
}
