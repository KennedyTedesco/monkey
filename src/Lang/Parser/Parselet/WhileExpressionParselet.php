<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser\Parselet;

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Ast\Expressions\WhileExpression;
use MonkeyLang\Lang\Parser\Parser;
use MonkeyLang\Lang\Parser\Precedence;
use MonkeyLang\Lang\Parser\Statements\BlockStatementParser;
use MonkeyLang\Lang\Token\TokenType;

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
