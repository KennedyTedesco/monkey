<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Types\BooleanLiteral;
use Monkey\Ast\Types\IntegerLiteral;
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

        if ($token->is(TokenType::T_INT)) {
            return new IntegerLiteral($token, (int) $token->literal());
        }

        if ($token->is(TokenType::T_FALSE, TokenType::T_TRUE)) {
            return new BooleanLiteral($token, $token->is(TokenType::T_TRUE));
        }
    }
}
