<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Types\FunctionLiteral;
use Monkey\Parser\BlockStatementParser;
use Monkey\Parser\FunctionParametersParser;
use Monkey\Parser\Parser;
use Monkey\Token\TokenType;

final class FunctionLiteralParselet implements PrefixParselet
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(): ?Expression
    {
        $token = $this->parser->curToken;

        if (!$this->parser->expectPeek(TokenType::T_LPAREN)) {
            return null;
        }

        $parameters = (new FunctionParametersParser())($this->parser);

        if (!$this->parser->expectPeek(TokenType::T_LBRACE)) {
            return null;
        }

        $body = (new BlockStatementParser())($this->parser);

        return new FunctionLiteral($token, $parameters, $body);
    }
}
