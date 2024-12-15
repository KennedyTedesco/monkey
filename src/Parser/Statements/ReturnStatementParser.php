<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Statements;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Ast\Statements\ReturnStatement;
use MonkeyLang\Parser\Parser;
use MonkeyLang\Parser\Precedence;
use MonkeyLang\Token\TokenType;

final class ReturnStatementParser
{
    public function __invoke(Parser $parser): ReturnStatement
    {
        $token = $parser->curToken();

        $parser->nextToken();

        /** @var Expression $valueExpression */
        $valueExpression = $parser->parseExpression(Precedence::LOWEST);

        if ($parser->peekToken()->is(TokenType::SEMICOLON)) {
            $parser->nextToken();
        }

        return new ReturnStatement($token, $valueExpression);
    }
}
