<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IndexExpression;
use Monkey\Parser\Parser;
use Monkey\Parser\Precedence;
use Monkey\Token\TokenType;

final readonly class IndexExpressionParselet implements InfixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(Expression $expression): ?Expression
    {
        $token = $this->parser->curToken;

        $this->parser->nextToken();

        $index = $this->parser->parseExpression(Precedence::LOWEST);

        if (!$this->parser->expectPeek(TokenType::RBRACKET)) {
            return null;
        }

        return new IndexExpression($token, $expression, $index);
    }
}
