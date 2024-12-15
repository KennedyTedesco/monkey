<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Parselet;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Ast\Types\ArrayLiteral;
use MonkeyLang\Parser\ExpressionListParser;
use MonkeyLang\Parser\Parser;
use MonkeyLang\Token\TokenType;

final readonly class ArrayParselet implements PrefixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(): Expression
    {
        $token = $this->parser->curToken();

        $elements = new ExpressionListParser()($this->parser, TokenType::RBRACKET);

        return new ArrayLiteral($token, $elements);
    }
}
