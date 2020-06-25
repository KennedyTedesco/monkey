<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Types\BooleanLiteral;
use Monkey\Ast\Types\FloatLiteral;
use Monkey\Ast\Types\IntegerLiteral;
use Monkey\Ast\Types\StringLiteral;
use Monkey\Parser\Parser;
use Monkey\Token\TokenType;

final class ScalarParselet implements PrefixParselet
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(): Expression
    {
        $token = $this->parser->curToken;

        switch (true) {
            case $token->is(TokenType::T_INT):
                return new IntegerLiteral($token, (int) $token->literal());
            case $token->is(TokenType::T_FLOAT):
                return new FloatLiteral($token, (float) $token->literal());
            case $token->is(TokenType::T_FALSE, TokenType::T_TRUE):
                return new BooleanLiteral($token, $token->is(TokenType::T_TRUE));
            case $token->is(TokenType::T_STRING):
                return new StringLiteral($token, $token->literal());
        }
    }
}
