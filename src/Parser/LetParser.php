<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Identifier;
use Monkey\Ast\LetStatement;
use Monkey\Token\TokenType;

final class LetParser
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(): ?LetStatement
    {
        $token = $this->parser->curToken;

        if (!$this->parser->peekTokenIs(TokenType::T_IDENT)) {
            return null;
        }

        $name = new Identifier(
            $this->parser->curToken,
            $this->parser->curToken->literal
        );

        if (!$this->parser->peekTokenIs(TokenType::T_ASSIGN)) {
            return null;
        }

        while (!$this->parser->curTokenIs(TokenType::T_SEMICOLON)) {
            $this->parser->nextToken();
        }

        return new LetStatement($token, $name);
    }
}
