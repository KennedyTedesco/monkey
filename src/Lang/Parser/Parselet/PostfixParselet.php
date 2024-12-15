<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser\Parselet;

use MonkeyLang\Lang\Ast\Expressions\Expression;

interface PostfixParselet
{
    public function parse(): Expression;
}
