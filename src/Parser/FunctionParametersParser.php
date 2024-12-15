<?php

declare(strict_types=1);

namespace MonkeyLang\Parser;

use MonkeyLang\Ast\Expressions\IdentifierExpression;
use MonkeyLang\Token\TokenType;

final class FunctionParametersParser
{
    /**
     * @return array<IdentifierExpression>
     */
    public function __invoke(Parser $parser): array
    {
        /** @var array<IdentifierExpression> $identifiers */
        $identifiers = [];

        if ($parser->peekToken()->is(TokenType::RPAREN)) {
            $parser->nextToken();

            return $identifiers;
        }

        $parser->nextToken();

        $identifiers[] = new IdentifierExpression($parser->curToken(), $parser->curToken()->literal());

        while ($parser->peekToken()->is(TokenType::COMMA)) {
            $parser->nextToken(2);

            $identifiers[] = new IdentifierExpression($parser->curToken(), $parser->curToken()->literal());
        }

        if (!$parser->expectPeek(TokenType::RPAREN)) {
            return [];
        }

        return $identifiers;
    }
}
