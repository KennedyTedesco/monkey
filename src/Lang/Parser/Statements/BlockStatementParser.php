<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser\Statements;

use MonkeyLang\Lang\Ast\Statements\BlockStatement;
use MonkeyLang\Lang\Ast\Statements\Statement;
use MonkeyLang\Lang\Parser\Parser;
use MonkeyLang\Lang\Token\TokenType;

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
