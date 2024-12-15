<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser\Parselet;

use MonkeyLang\Lang\Ast\Expressions\CallExpression;
use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Parser\ExpressionListParser;
use MonkeyLang\Lang\Parser\Parser;
use MonkeyLang\Lang\Token\TokenType;

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
