<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\TokenType;

final class CallArgumentsParser
{
    /**
     * @return array<Expression>
     */
    public function __invoke(Parser $parser): array
    {
        /** @var array<Expression> $args */
        $args = [];

        if ($parser->peekToken->is(TokenType::T_RPAREN)) {
            $parser->nextToken();

            return $args;
        }

        $parser->nextToken();

        $args[] = $parser->parseExpression(Precedence::LOWEST);

        while ($parser->peekToken->is(TokenType::T_COMMA)) {
            $parser->nextToken(2);

            $args[] = $parser->parseExpression(Precedence::LOWEST);
        }

        if (!$parser->expectPeek(TokenType::T_RPAREN)) {
            return [];
        }

        return $args;
    }
}
