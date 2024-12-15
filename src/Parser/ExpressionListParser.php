<?php

declare(strict_types=1);

namespace MonkeyLang\Parser;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Token\TokenType;

final class ExpressionListParser
{
    /**
     * @return array<Expression>
     */
    public function __invoke(Parser $parser, TokenType $endTokenType): array
    {
        /** @var array<Expression> $args */
        $args = [];

        if ($parser->peekToken()->is($endTokenType)) {
            $parser->nextToken();

            return $args;
        }

        $parser->nextToken();

        $args[] = $parser->parseExpression(Precedence::LOWEST);

        while ($parser->peekToken()->is(TokenType::COMMA)) {
            $parser->nextToken(2);

            $args[] = $parser->parseExpression(Precedence::LOWEST);
        }

        if (!$parser->expectPeek($endTokenType)) {
            return [];
        }

        return array_filter($args);
    }
}
