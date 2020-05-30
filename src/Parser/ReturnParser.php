<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\ReturnStatement;
use Monkey\Token\TokenType;

final class ReturnParser
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(): ReturnStatement
    {
        $token = $this->parser->curToken;

        $this->parser->nextToken();

        while (!$this->parser->curTokenIs(TokenType::T_SEMICOLON)) {
            $this->parser->nextToken();
        }

        return new ReturnStatement($token);
    }
}
