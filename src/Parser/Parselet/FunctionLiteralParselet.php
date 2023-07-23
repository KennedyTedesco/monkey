<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Types\FunctionLiteral;
use Monkey\Parser\FunctionParametersParser;
use Monkey\Parser\Parser;
use Monkey\Parser\Statements\BlockStatementParser;
use Monkey\Token\Token;
use Monkey\Token\TokenType;

final readonly class FunctionLiteralParselet implements PrefixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(): ?Expression
    {
        /** @var Token $token */
        $token = $this->parser->curToken;

        if (!$this->parser->expectPeek(TokenType::LPAREN)) {
            return null;
        }

        $parameters = (new FunctionParametersParser())($this->parser);

        if (!$this->parser->expectPeek(TokenType::LBRACE)) {
            return null;
        }

        $body = (new BlockStatementParser())($this->parser);

        return new FunctionLiteral($token, $parameters, $body);
    }
}
