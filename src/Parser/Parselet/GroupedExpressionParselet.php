<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Parser\Parser;
use Monkey\Parser\Precedence;
use Monkey\Token\TokenType;

final class GroupedExpressionParselet implements PrefixParselet
{
    public function __construct(private Parser $parser)
    {
    }

    public function parse(): ?Expression
    {
        $this->parser->nextToken();

        /** @var Expression $expression */
        $expression = $this->parser->parseExpression(Precedence::LOWEST);

        if (!$this->parser->expectPeek(TokenType::T_RPAREN)) {
            return null;
        }

        return $expression;
    }
}
