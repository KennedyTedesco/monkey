<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Parselet;

use MonkeyLang\Ast\Expressions\BinaryExpression;
use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Parser\Parser;

final readonly class BinaryOperatorParselet implements InfixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(Expression $expression): Expression
    {
        $token = $this->parser->curToken();

        $this->parser->nextToken();

        /** @var Expression $right */
        $right = $this->parser->parseExpression($token->type()->precedence());

        return new BinaryExpression($token, $token->literal(), $expression, $right);
    }
}
