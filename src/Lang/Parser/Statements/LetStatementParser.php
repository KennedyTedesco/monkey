<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser\Statements;

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Ast\Expressions\IdentifierExpression;
use MonkeyLang\Lang\Ast\Statements\LetStatement;
use MonkeyLang\Lang\Parser\Parser;
use MonkeyLang\Lang\Parser\Precedence;
use MonkeyLang\Lang\Token\TokenType;

final class LetStatementParser
{
    public function __invoke(Parser $parser): ?LetStatement
    {
        $token = $parser->curToken();

        if (!$parser->expectPeek(TokenType::IDENT)) {
            return null;
        }

        $identifierExpression = new IdentifierExpression(
            $parser->curToken(),
            $parser->curToken()->literal(),
        );

        if (!$parser->expectPeek(TokenType::ASSIGN)) {
            return null;
        }

        $parser->nextToken();

        /** @var Expression $value */
        $value = $parser->parseExpression(Precedence::LOWEST);

        if ($parser->peekToken()->is(TokenType::SEMICOLON)) {
            $parser->nextToken();
        }

        return new LetStatement($token, $identifierExpression, $value);
    }
}
