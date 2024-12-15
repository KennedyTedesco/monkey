<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser\Parselet;

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Parser\Parser;
use MonkeyLang\Lang\Parser\Precedence;
use MonkeyLang\Lang\Token\TokenType;

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
