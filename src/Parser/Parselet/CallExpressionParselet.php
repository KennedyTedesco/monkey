<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\CallExpression;
use Monkey\Ast\Expressions\Expression;
use Monkey\Parser\ExpressionListParser;
use Monkey\Parser\Parser;
use Monkey\Token\TokenType;

final readonly class CallExpressionParselet implements InfixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(Expression $expression): Expression
    {
        $token = $this->parser->curToken();

        $arguments = (new ExpressionListParser())($this->parser, TokenType::RPAREN);

        return new CallExpression($token, $expression, $arguments);
    }
}
