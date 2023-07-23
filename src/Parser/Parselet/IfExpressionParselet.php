<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IfExpression;
use Monkey\Parser\Parser;
use Monkey\Parser\Precedence;
use Monkey\Parser\Statements\BlockStatementParser;
use Monkey\Token\Token;
use Monkey\Token\TokenType;

final readonly class IfExpressionParselet implements PrefixParselet
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

        $this->parser->nextToken();

        /** @var Expression $condition */
        $condition = $this->parser->parseExpression(Precedence::LOWEST);

        if (!$this->parser->expectPeek(TokenType::RPAREN)) {
            return null;
        }

        if (!$this->parser->expectPeek(TokenType::LBRACE)) {
            return null;
        }

        $consequence = (new BlockStatementParser())($this->parser);

        $alternative = null;

        if ($this->parser->peekToken()->is(TokenType::ELSE)) {
            $this->parser->nextToken();

            if (!$this->parser->expectPeek(TokenType::LBRACE)) {
                return null;
            }

            $alternative = (new BlockStatementParser())($this->parser);
        }

        return new IfExpression($token, $condition, $consequence, $alternative);
    }
}
