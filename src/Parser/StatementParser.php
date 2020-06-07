<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Statements\Statement;
use Monkey\Token\TokenType;

final class StatementParser
{
    public function __invoke(Parser $parser): ?Statement
    {
        switch ($parser->curToken->type()) {
            case TokenType::T_LET:
                return (new LetStatementParser())($parser);
            case TokenType::T_RETURN:
                return (new ReturnStatementParser())($parser);
            default:
                return (new ExpressionStatementParser())($parser);
        }
    }
}
