<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IndexExpression;
use Monkey\Parser\Parser;
use Monkey\Parser\Precedence;
use Monkey\Token\TokenType;

final class IndexExpressionParselet implements InfixParselet
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(Expression $left): ?Expression
    {
        $token = $this->parser->curToken;

        $this->parser->nextToken();

        $index = $this->parser->parseExpression(Precedence::LOWEST);

        if (!$this->parser->expectPeek(TokenType::T_RBRACKET)) {
            return null;
        }

        return new IndexExpression($token, $left, $index);
    }
}
