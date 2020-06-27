<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Statements\Statement;
use Monkey\Token\TokenType;

final class StatementParser
{
    public function __invoke(Parser $parser): Statement
    {
        switch (true) {
            case $parser->curToken->is(TokenType::T_IDENT) && $parser->peekToken->is(TokenType::T_ASSIGN):
                return (new AssignStatementParser())($parser);
            case $parser->curToken->is(TokenType::T_LET):
                return (new LetStatementParser())($parser);
            case $parser->curToken->is(TokenType::T_RETURN):
                return (new ReturnStatementParser())($parser);
            default:
                return (new ExpressionStatementParser())($parser);
        }
    }
}
