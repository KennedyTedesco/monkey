<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\InfixExpression;
use Monkey\Parser\Parser;

final class InfixParselet implements Parselet
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(Expression $left = null): Expression
    {
        $token = $this->parser->curToken;

        $this->parser->nextToken();

        /** @var Expression $leftExp */
        $leftExp = $left;

        /** @var Expression $rightExp */
        $rightExp = $this->parser->parseExpression($this->parser->precedence($token));

        return new InfixExpression($token, $token->literal, $leftExp, $rightExp);
    }
}
