<?php

declare(strict_types=1);

namespace Monkey\Parser\Statements;

use Monkey\Ast\Statements\BlockStatement;
use Monkey\Ast\Statements\Statement;
use Monkey\Parser\Parser;
use Monkey\Token\TokenType;

final class BlockStatementParser
{
    public function __invoke(Parser $parser): BlockStatement
    {
        $token = $parser->curToken();

        $parser->nextToken();

        /** @var array<Statement> $statements */
        $statements = [];

        while (!$parser->curToken()->is(TokenType::RBRACE) && !$parser->curToken()->is(TokenType::EOF)) {
            $statements[] = (new StatementParser())($parser);

            $parser->nextToken();
        }

        return new BlockStatement($token, $statements);
    }
}
