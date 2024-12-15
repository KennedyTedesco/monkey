<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Statements;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Ast\Expressions\IdentifierExpression;
use MonkeyLang\Ast\Statements\AssignStatement;
use MonkeyLang\Parser\Parser;
use MonkeyLang\Parser\Precedence;
use MonkeyLang\Token\TokenType;

final class AssignStatementParser
{
    public function __invoke(Parser $parser): ?AssignStatement
    {
        $token = $parser->curToken();

        if (!$parser->expectPeek(TokenType::ASSIGN)) {
            return null;
        }

        $parser->nextToken();

        /** @var Expression $value */
        $value = $parser->parseExpression(Precedence::LOWEST);

        if ($parser->peekToken()->is(TokenType::SEMICOLON)) {
            $parser->nextToken();
        }

        return new AssignStatement($token, new IdentifierExpression($token, $token->literal()), $value);
    }
}
