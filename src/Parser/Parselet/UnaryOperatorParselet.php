<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\UnaryExpression;
use Monkey\Parser\Parser;
use Monkey\Parser\Precedence;
use Monkey\Token\Token;

final readonly class UnaryOperatorParselet implements PrefixParselet
{
    public function __construct(
        public Parser $parser,
    ) {
    }

    public function parse(): Expression
    {
        /** @var Token $token */
        $token = $this->parser->curToken;

        $this->parser->nextToken();

        /** @var Expression $right */
        $right = $this->parser->parseExpression(Precedence::PREFIX);

        return new UnaryExpression($token, $token->literal(), $right);
    }
}
