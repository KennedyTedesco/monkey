<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Statements\ReturnStatement;
use Monkey\Token\TokenType;

final class ReturnStatementParser
{
    public function __invoke(Parser $parser): ReturnStatement
    {
        $token = $parser->curToken;

        $parser->nextToken();

        $valueExpression = $parser->parseExpression(Precedence::LOWEST);

        if ($parser->peekToken->is(TokenType::T_SEMICOLON)) {
            $parser->nextToken();
        }

        return new ReturnStatement($token, $valueExpression);
    }
}
