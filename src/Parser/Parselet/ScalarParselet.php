<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Parselet;

use InvalidArgumentException;
use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Ast\Types\BooleanLiteral;
use MonkeyLang\Ast\Types\FloatLiteral;
use MonkeyLang\Ast\Types\IntegerLiteral;
use MonkeyLang\Ast\Types\StringLiteral;
use MonkeyLang\Parser\Parser;
use MonkeyLang\Token\TokenType;

final class ScalarParselet implements PrefixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(): Expression
    {
        $token = $this->parser->curToken();

        return match ($token->type()) {
            TokenType::INT => new IntegerLiteral($token, (int)$token->literal()),
            TokenType::FLOAT => new FloatLiteral($token, (float)$token->literal()),
            TokenType::FALSE, TokenType::TRUE => new BooleanLiteral($token, $token->is(TokenType::TRUE)),
            TokenType::STRING => new StringLiteral($token, $token->literal()),
            default => throw new InvalidArgumentException("Unexpected token type: {$token->type()->lexeme()}"),
        };
    }
}
