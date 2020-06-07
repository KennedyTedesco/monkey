<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Types\Boolean;
use Monkey\Ast\Types\Integer;
use Monkey\Parser\Parser;
use Monkey\Token\TokenType;

final class LiteralParselet implements PrefixParselet
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
            return new Integer($token, (int) $token->literal());
        }

        if ($token->is(TokenType::T_FALSE) || $token->is(TokenType::T_TRUE)) {
            return new Boolean($token, $token->is(TokenType::T_TRUE));
        }
    }
}
