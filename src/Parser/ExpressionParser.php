<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Statements\ExpressionStatement;
use Monkey\Token\TokenType;

final class ExpressionParser
{
    public function __invoke(Parser $parser): ExpressionStatement
    {
        /** @var Expression $expression */
        $expression = $parser->parseExpression(Precedence::LOWEST);

        $statement = new ExpressionStatement($parser->curToken, $expression);

        if ($parser->peekTokenIs(TokenType::T_SEMICOLON)) {
            $parser->nextToken();
        }

        return $statement;
    }
}
