<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Statements\ReturnStatement;
use Monkey\Token\TokenType;

final class ReturnParser
{
    public function __invoke(Parser $parser): ReturnStatement
    {
        $token = $parser->curToken;

        $parser->nextToken();

        while (!$parser->curToken->is(TokenType::T_SEMICOLON)) {
            $parser->nextToken();
        }

        return new ReturnStatement($token);
    }
}
