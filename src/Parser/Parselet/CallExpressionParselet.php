<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Parselet;

use MonkeyLang\Ast\Expressions\CallExpression;
use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Parser\ExpressionListParser;
use MonkeyLang\Parser\Parser;
use MonkeyLang\Token\TokenType;

final readonly class CallExpressionParselet implements InfixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(Expression $expression): Expression
    {
        $token = $this->parser->curToken();

        $arguments = new ExpressionListParser()($this->parser, TokenType::RPAREN);

        return new CallExpression($token, $expression, $arguments);
    }
}
