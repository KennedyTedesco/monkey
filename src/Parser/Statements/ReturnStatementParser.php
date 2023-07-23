<?php

declare(strict_types=1);

namespace Monkey\Parser\Statements;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Statements\ReturnStatement;
use Monkey\Parser\Parser;
use Monkey\Parser\Precedence;
use Monkey\Token\TokenType;

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
