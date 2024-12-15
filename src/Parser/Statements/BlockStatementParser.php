<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Statements;

use MonkeyLang\Ast\Statements\BlockStatement;
use MonkeyLang\Ast\Statements\Statement;
use MonkeyLang\Parser\Parser;
use MonkeyLang\Token\TokenType;

final class BlockStatementParser
{
    public function __invoke(Parser $parser): BlockStatement
    {
        $token = $parser->curToken();

        $parser->nextToken();

        /** @var array<Statement> $statements */
        $statements = [];

        while (!$parser->curToken()->is(TokenType::RBRACE) && !$parser->curToken()->is(TokenType::EOF)) {
            $statement = new StatementParser()($parser);

            if ($statement instanceof Statement) {
                $statements[] = $statement;
            }

            $parser->nextToken();
        }

        return new BlockStatement($token, $statements);
    }
}
