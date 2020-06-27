<?php

declare(strict_types=1);

namespace Monkey\Parser\Statements;

use Monkey\Ast\Statements\PrintStatement;
use Monkey\Parser\Parser;
use Monkey\Parser\Precedence;
use Monkey\Token\TokenType;

final class PrintStatementParser
{
    public function __invoke(Parser $parser): ?PrintStatement
    {
        $token = $parser->curToken;

        $parser->nextToken();

        $value = $parser->parseExpression(Precedence::LOWEST);

        if ($parser->peekToken->is(TokenType::T_SEMICOLON)) {
            $parser->nextToken();
        }

        return new PrintStatement($token, $value);
    }
}
