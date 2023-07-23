<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use InvalidArgumentException;
use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Types\BooleanLiteral;
use Monkey\Ast\Types\FloatLiteral;
use Monkey\Ast\Types\IntegerLiteral;
use Monkey\Ast\Types\StringLiteral;
use Monkey\Parser\Parser;
use Monkey\Token\TokenType;

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
