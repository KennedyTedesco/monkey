<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Expressions\Identifier;
use Monkey\Ast\Statements\LetStatement;
use Monkey\Token\TokenType;

final class LetParser
{
    public function __invoke(Parser $parser): ?LetStatement
    {
        $token = $parser->curToken;

        if (!$parser->expectPeek(TokenType::T_IDENT)) {
            return null;
        }

        $name = new Identifier(
            $parser->curToken,
            $parser->curToken->literal
        );

        if (!$parser->expectPeek(TokenType::T_ASSIGN)) {
            return null;
        }

        while (!$parser->curTokenIs(TokenType::T_SEMICOLON)) {
            $parser->nextToken();
        }

        return new LetStatement($token, $name);
    }
}
