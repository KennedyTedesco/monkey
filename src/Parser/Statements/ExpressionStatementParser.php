<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Statements;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Ast\Statements\ExpressionStatement;
use MonkeyLang\Parser\Parser;
use MonkeyLang\Parser\Precedence;
use MonkeyLang\Token\TokenType;

final class ExpressionStatementParser
{
    public function __invoke(Parser $parser): ExpressionStatement
    {
        $token = $parser->curToken();

        /** @var Expression $expression */
        $expression = $parser->parseExpression(Precedence::LOWEST);

        $expressionStatement = new ExpressionStatement($token, $expression);

        if ($parser->peekToken()->is(TokenType::SEMICOLON)) {
            $parser->nextToken();
        }

        return $expressionStatement;
    }
}
