<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser\Parselet;

use InvalidArgumentException;
use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Ast\Types\BooleanLiteral;
use MonkeyLang\Lang\Ast\Types\FloatLiteral;
use MonkeyLang\Lang\Ast\Types\IntegerLiteral;
use MonkeyLang\Lang\Ast\Types\StringLiteral;
use MonkeyLang\Lang\Parser\Parser;
use MonkeyLang\Lang\Token\TokenType;

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
