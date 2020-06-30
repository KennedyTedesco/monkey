<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;

interface PostfixParselet
{
    public function parse(): Expression;
}
