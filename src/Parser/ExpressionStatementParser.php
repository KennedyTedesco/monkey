<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Statements\ExpressionStatement;
use Monkey\Token\TokenType;

final class ExpressionStatementParser
{
    public function __invoke(Parser $parser): ExpressionStatement
    {
        $token = $parser->curToken;

        /** @var Expression $expression */
        $expression = $parser->parseExpression(Precedence::LOWEST);

        $statement = new ExpressionStatement($token, $expression);

        if ($parser->peekToken->is(TokenType::T_SEMICOLON)) {
            $parser->nextToken();
        }

        return $statement;
    }
}
