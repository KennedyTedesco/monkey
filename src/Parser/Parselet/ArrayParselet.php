<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Types\ArrayLiteral;
use Monkey\Parser\ExpressionListParser;
use Monkey\Parser\Parser;
use Monkey\Token\TokenType;

final class ArrayParselet implements PrefixParselet
{
    public function __construct(private Parser $parser)
    {
    }

    public function parse(): Expression
    {
        $token = $this->parser->curToken;

        $elements = (new ExpressionListParser())($this->parser, TokenType::T_RBRACKET);

        return new ArrayLiteral($token, $elements);
    }
}
