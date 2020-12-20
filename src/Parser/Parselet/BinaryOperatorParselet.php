<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\BinaryExpression;
use Monkey\Ast\Expressions\Expression;
use Monkey\Parser\Parser;

final class BinaryOperatorParselet implements InfixParselet
{
    public function __construct(private Parser $parser)
    {
    }

    public function parse(Expression $left): Expression
    {
        $token = $this->parser->curToken;

        $this->parser->nextToken();

        /** @var Expression $right */
        $right = $this->parser->parseExpression(
            $this->parser->precedence($token)
        );

        return new BinaryExpression($token, $token->literal(), $left, $right);
    }
}
