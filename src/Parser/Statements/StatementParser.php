<?php

declare(strict_types=1);

namespace Monkey\Parser\Statements;

use Monkey\Ast\Statements\Statement;
use Monkey\Parser\Parser;
use Monkey\Token\TokenType;

final class StatementParser
{
    public function __invoke(Parser $parser): ?Statement
    {
        return match (true) {
            $parser->curToken()->is(TokenType::IDENT) && $parser->peekToken()->is(TokenType::ASSIGN) =>
                new AssignStatementParser()($parser),

            $parser->curToken()->is(TokenType::LET) =>
                new LetStatementParser()($parser),

            $parser->curToken()->is(TokenType::RETURN) =>
                new ReturnStatementParser()($parser),

            default => new ExpressionStatementParser()($parser),
        };
    }
}
