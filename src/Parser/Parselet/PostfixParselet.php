<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Parselet;

use MonkeyLang\Ast\Expressions\Expression;

interface PostfixParselet
{
    public function parse(): Expression;
}
