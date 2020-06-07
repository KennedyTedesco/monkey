<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\InfixExpression;
use Monkey\Parser\Parser;

final class BinaryOperatorParselet implements InfixParselet
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(Expression $left): Expression
    {
        $token = $this->parser->curToken;

        $this->parser->nextToken();

        /** @var Expression $rightExp */
        $rightExp = $this->parser->parseExpression($this->parser->precedence($token));

        return new InfixExpression($token, $token->literal, $left, $rightExp);
    }
}
