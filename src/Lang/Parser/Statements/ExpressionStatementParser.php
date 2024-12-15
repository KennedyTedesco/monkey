<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser\Statements;

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Ast\Statements\ExpressionStatement;
use MonkeyLang\Lang\Parser\Parser;
use MonkeyLang\Lang\Parser\Precedence;
use MonkeyLang\Lang\Token\TokenType;

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
