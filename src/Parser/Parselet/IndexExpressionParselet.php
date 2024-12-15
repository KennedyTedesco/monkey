<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Parselet;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Ast\Expressions\IndexExpression;
use MonkeyLang\Parser\Parser;
use MonkeyLang\Parser\Precedence;
use MonkeyLang\Token\TokenType;

final readonly class IndexExpressionParselet implements InfixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(Expression $expression): ?Expression
    {
        $token = $this->parser->curToken();

        $this->parser->nextToken();

        $index = $this->parser->parseExpression(Precedence::LOWEST);

        if (!$index instanceof Expression) {
            return null;
        }

        if (!$this->parser->expectPeek(TokenType::RBRACKET)) {
            return null;
        }

        return new IndexExpression($token, $expression, $index);
    }
}
