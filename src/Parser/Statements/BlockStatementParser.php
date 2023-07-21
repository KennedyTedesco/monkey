<?php

declare(strict_types=1);

namespace Monkey\Parser\Statements;

use Monkey\Ast\Statements\BlockStatement;
use Monkey\Parser\Parser;
use Monkey\Token\TokenType;

final class BlockStatementParser
{
    public function __invoke(Parser $parser): BlockStatement
    {
        $statements = [];
        $token = $parser->curToken;

        $parser->nextToken();

        while (!$parser->curToken->is(TokenType::T_RBRACE) && !$parser->curToken->is(TokenType::T_EOF)) {
            $statement = (new StatementParser())($parser);
            $statements[] = $statement;

            $parser->nextToken();
        }

        return new BlockStatement($token, $statements);
    }
}
