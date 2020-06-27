<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Ast\Statements\AssignStatement;
use Monkey\Token\TokenType;

final class AssignStatementParser
{
    public function __invoke(Parser $parser): ?AssignStatement
    {
        $token = $parser->curToken;

        if (!$parser->expectPeek(TokenType::T_ASSIGN)) {
            return null;
        }

        $parser->nextToken();

        $value = $parser->parseExpression(Precedence::LOWEST);

        if ($parser->peekToken->is(TokenType::T_SEMICOLON)) {
            $parser->nextToken();
        }

        return new AssignStatement($token, new IdentifierExpression($token, $token->literal()), $value);
    }
}
