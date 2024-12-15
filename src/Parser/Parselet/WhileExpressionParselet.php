<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Parselet;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Ast\Expressions\WhileExpression;
use MonkeyLang\Parser\Parser;
use MonkeyLang\Parser\Precedence;
use MonkeyLang\Parser\Statements\BlockStatementParser;
use MonkeyLang\Token\TokenType;

final readonly class WhileExpressionParselet implements PrefixParselet
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

        $this->parser->nextToken();

        /** @var Expression $condition */
        $condition = $this->parser->parseExpression(Precedence::LOWEST);

        if (!$this->parser->expectPeek(TokenType::RPAREN)) {
            return null;
        }

        if (!$this->parser->expectPeek(TokenType::LBRACE)) {
            return null;
        }

        $consequence = new BlockStatementParser()($this->parser);

        return new WhileExpression($token, $condition, $consequence);
    }
}
