<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\PrefixExpression;
use Monkey\Ast\Node;
use Monkey\Parser\Precedence;

final class PrefixExpressionParselet extends Parselet
{
    public function parse(): Node
    {
        $token = $this->parser->curToken;

        $this->parser->nextToken();

        $right = $this->parser->parseExpression(Precedence::PREFIX);

        return new PrefixExpression($token, $right, $token->literal);
    }
}
