<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Parselet;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Ast\Types\FunctionLiteral;
use MonkeyLang\Parser\FunctionParametersParser;
use MonkeyLang\Parser\Parser;
use MonkeyLang\Parser\Statements\BlockStatementParser;
use MonkeyLang\Token\TokenType;

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
