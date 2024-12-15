<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser\Statements;

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Ast\Statements\ReturnStatement;
use MonkeyLang\Lang\Parser\Parser;
use MonkeyLang\Lang\Parser\Precedence;
use MonkeyLang\Lang\Token\TokenType;

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
