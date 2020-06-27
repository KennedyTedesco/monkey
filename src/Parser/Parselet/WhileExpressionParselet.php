<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\WhileExpression;
use Monkey\Parser\BlockStatementParser;
use Monkey\Parser\Parser;
use Monkey\Parser\Precedence;
use Monkey\Token\TokenType;

final class WhileExpressionParselet implements PrefixParselet
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

        $this->parser->nextToken();

        /** @var Expression $condition */
        $condition = $this->parser->parseExpression(Precedence::LOWEST);

        if (!$this->parser->expectPeek(TokenType::T_RPAREN)) {
            return null;
        }

        if (!$this->parser->expectPeek(TokenType::T_LBRACE)) {
            return null;
        }

        $consequence = (new BlockStatementParser())($this->parser);

        return new WhileExpression($token, $condition, $consequence);
    }
}
