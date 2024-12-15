<?php

declare(strict_types=1);

namespace MonkeyLang\Parser\Parselet;

use MonkeyLang\Ast\Expressions\Expression;

interface InfixParselet
{
    public function parse(Expression $expression): ?Expression;
}
