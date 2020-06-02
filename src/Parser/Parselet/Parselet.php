<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\Expression;

interface Parselet
{
    public function parse(): Expression;
}
