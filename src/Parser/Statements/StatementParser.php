<?php

declare(strict_types=1);

namespace Monkey\Parser\Statements;

use Monkey\Ast\Statements\Statement;
use Monkey\Parser\Parser;
use Monkey\Token\TokenType;

final class StatementParser
{
    public function __invoke(Parser $parser): Statement
    {
        return match (true) {
            $parser->curToken->is(TokenType::T_IDENT) && $parser->peekToken->is(TokenType::T_ASSIGN) => (new AssignStatementParser())($parser),
            $parser->curToken->is(TokenType::T_LET) => (new LetStatementParser())($parser),
            $parser->curToken->is(TokenType::T_RETURN) => (new ReturnStatementParser())($parser),
            default => (new ExpressionStatementParser())($parser),
        };
    }
}
