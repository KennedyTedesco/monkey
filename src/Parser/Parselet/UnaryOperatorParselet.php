<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Parselet;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Ast\Expressions\UnaryExpression;
use MonkeyLang\Parser\Parser;
use MonkeyLang\Parser\Precedence;

final readonly class UnaryOperatorParselet implements PrefixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(): Expression
    {
        $token = $this->parser->curToken();

        $this->parser->nextToken();

        /** @var Expression $right */
        $right = $this->parser->parseExpression(Precedence::PREFIX);

        return new UnaryExpression($token, $token->literal(), $right);
    }
}
