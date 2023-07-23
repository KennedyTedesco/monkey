<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\BinaryExpression;
use Monkey\Ast\Expressions\Expression;
use Monkey\Parser\Parser;

final readonly class BinaryOperatorParselet implements InfixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(Expression $expression): Expression
    {
        $token = $this->parser->curToken();

        $this->parser->nextToken();

        /** @var Expression $right */
        $right = $this->parser->parseExpression(
            $this->parser->precedence($token),
        );

        return new BinaryExpression($token, $token->literal(), $expression, $right);
    }
}
