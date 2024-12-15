<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser\Statements;

use MonkeyLang\Lang\Ast\Statements\Statement;
use MonkeyLang\Lang\Parser\Parser;
use MonkeyLang\Lang\Token\TokenType;

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
