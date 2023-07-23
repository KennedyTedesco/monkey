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

final readonly class ScalarParselet implements PrefixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(): Expression
    {
        $token = $this->parser->curToken;

        if ($token->is(TokenType::INT)) {
            return new IntegerLiteral($token, (int)$token->literal());
        }

        if ($token->is(TokenType::FLOAT)) {
            return new FloatLiteral($token, (float)$token->literal());
        }

        if ($token->is(TokenType::FALSE) || $token->is(TokenType::TRUE)) {
            return new BooleanLiteral($token, $token->is(TokenType::TRUE));
        }

        if ($token->is(TokenType::STRING)) {
            return new StringLiteral($token, $token->literal());
        }
    }
}
