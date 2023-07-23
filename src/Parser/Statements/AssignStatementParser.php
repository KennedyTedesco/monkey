<?php

declare(strict_types=1);

namespace Monkey\Parser\Statements;

use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Ast\Statements\AssignStatement;
use Monkey\Parser\Parser;
use Monkey\Parser\Precedence;
use Monkey\Token\TokenType;

final class AssignStatementParser
{
    public function __invoke(Parser $parser): ?AssignStatement
    {
        $token = $parser->curToken;

        if (!$parser->expectPeek(TokenType::ASSIGN)) {
            return null;
        }

        $parser->nextToken();

        $value = $parser->parseExpression(Precedence::LOWEST);

        if ($parser->peekToken->is(TokenType::SEMICOLON)) {
            $parser->nextToken();
        }

        return new AssignStatement($token, new IdentifierExpression($token, $token->literal()), $value);
    }
}
