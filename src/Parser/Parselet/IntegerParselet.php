<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Types\IntegerLiteral;

final class IntegerParselet extends Parselet
{
    public function parse(): Expression
    {
        return new IntegerLiteral($this->parser->curToken, (int) $this->parser->curToken->literal);
    }
}
