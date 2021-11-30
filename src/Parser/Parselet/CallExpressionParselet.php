<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\CallExpression;
use Monkey\Ast\Expressions\Expression;
use Monkey\Parser\ExpressionListParser;
use Monkey\Parser\Parser;
use Monkey\Token\TokenType;

final class CallExpressionParselet implements InfixParselet
{
    public function __construct(private Parser $parser)
    {
    }

    public function parse(Expression $expression): Expression
    {
        $arguments = (new ExpressionListParser())($this->parser, TokenType::T_RPAREN);

        return new CallExpression($this->parser->curToken, $expression, $arguments);
    }
}
