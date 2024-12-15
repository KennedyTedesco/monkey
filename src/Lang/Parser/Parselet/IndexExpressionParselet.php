<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser\Parselet;

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Ast\Expressions\IndexExpression;
use MonkeyLang\Lang\Parser\Parser;
use MonkeyLang\Lang\Parser\Precedence;
use MonkeyLang\Lang\Token\TokenType;

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
