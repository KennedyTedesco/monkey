<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser\Parselet;

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Ast\Types\ArrayLiteral;
use MonkeyLang\Lang\Parser\ExpressionListParser;
use MonkeyLang\Lang\Parser\Parser;
use MonkeyLang\Lang\Token\TokenType;

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
