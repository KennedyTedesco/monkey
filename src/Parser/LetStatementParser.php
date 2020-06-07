<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Ast\Statements\LetStatement;
use Monkey\Token\TokenType;

final class LetStatementParser
{
    public function __invoke(Parser $parser): ?LetStatement
    {
        $token = $parser->curToken;

        if (!$parser->expectPeek(TokenType::T_IDENT)) {
            return null;
        }

        $name = new IdentifierExpression(
            $parser->curToken,
            $parser->curToken->literal()
        );

        if (!$parser->expectPeek(TokenType::T_ASSIGN)) {
            return null;
        }

        while (!$parser->curToken->is(TokenType::T_SEMICOLON)) {
            $parser->nextToken();
        }

        return new LetStatement($token, $name);
    }
}
