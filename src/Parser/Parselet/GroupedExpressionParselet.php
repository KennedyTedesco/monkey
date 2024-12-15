<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Parselet;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Parser\Parser;
use MonkeyLang\Parser\Precedence;
use MonkeyLang\Token\TokenType;

final readonly class GroupedExpressionParselet implements PrefixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(): ?Expression
    {
        $this->parser->nextToken();

        /** @var Expression $expression */
        $expression = $this->parser->parseExpression(Precedence::LOWEST);

        if (!$this->parser->expectPeek(TokenType::RPAREN)) {
            return null;
        }

        return $expression;
    }
}
