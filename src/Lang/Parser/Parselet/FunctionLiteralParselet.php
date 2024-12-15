<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser\Parselet;

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Ast\Types\FunctionLiteral;
use MonkeyLang\Lang\Parser\FunctionParametersParser;
use MonkeyLang\Lang\Parser\Parser;
use MonkeyLang\Lang\Parser\Statements\BlockStatementParser;
use MonkeyLang\Lang\Token\TokenType;

final readonly class FunctionLiteralParselet implements PrefixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(): ?Expression
    {
        $token = $this->parser->curToken();

        if (!$this->parser->expectPeek(TokenType::LPAREN)) {
            return null;
        }

        $parameters = new FunctionParametersParser()($this->parser);

        if (!$this->parser->expectPeek(TokenType::LBRACE)) {
            return null;
        }

        $body = new BlockStatementParser()($this->parser);

        return new FunctionLiteral($token, $parameters, $body);
    }
}
