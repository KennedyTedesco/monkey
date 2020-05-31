<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\ExpressionStatement;
use Monkey\Token\TokenType;

final class ExpressionParser
{
    public function __invoke(Parser $parser): ExpressionStatement
    {
        $statement = new ExpressionStatement(
            $parser->curToken,
            $parser->parseExpression(Precedence::LOWEST)
        );

        if ($parser->peekTokenIs(TokenType::T_SEMICOLON)) {
            $parser->nextToken();
        }

        return $statement;
    }
}
