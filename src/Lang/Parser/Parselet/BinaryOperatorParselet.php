<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser\Parselet;

use MonkeyLang\Lang\Ast\Expressions\BinaryExpression;
use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Parser\Parser;

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
