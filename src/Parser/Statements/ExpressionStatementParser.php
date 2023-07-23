<?php

declare(strict_types=1);

namespace Monkey\Parser\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Statements\ExpressionStatement;
use Monkey\Parser\Parser;
use Monkey\Parser\Precedence;
use Monkey\Token\TokenType;

final class ExpressionStatementParser
{
    public function __invoke(Parser $parser): ExpressionStatement
    {
        $token = $parser->curToken;

        /** @var Expression $expression */
        $expression = $parser->parseExpression(Precedence::LOWEST);

        $expressionStatement = new ExpressionStatement($token, $expression);

        if ($parser->peekToken->is(TokenType::SEMICOLON)) {
            $parser->nextToken();
        }

        return $expressionStatement;
    }
}
