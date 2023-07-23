<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Types\ArrayLiteral;
use Monkey\Parser\ExpressionListParser;
use Monkey\Parser\Parser;
use Monkey\Token\Token;
use Monkey\Token\TokenType;

final readonly class ArrayParselet implements PrefixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(): Expression
    {
        /** @var Token $token */
        $token = $this->parser->curToken;

        $elements = (new ExpressionListParser())($this->parser, TokenType::RBRACKET);

        return new ArrayLiteral($token, $elements);
    }
}
