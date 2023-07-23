<?php

declare(strict_types=1);

namespace Monkey\Parser\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Ast\Statements\LetStatement;
use Monkey\Parser\Parser;
use Monkey\Parser\Precedence;
use Monkey\Token\TokenType;

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
