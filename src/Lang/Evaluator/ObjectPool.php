<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Object\BooleanObject;
use MonkeyLang\Lang\Object\NullObject;

final class ObjectPool
{
    public function __construct(
        private(set) BooleanObject $true = new BooleanObject(true),
        private(set) BooleanObject $false = new BooleanObject(false),
        private(set) NullObject $null = new NullObject(null),
    ) {
    }
}
