<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Command;

use MonkeyLang\Monkey\Config\Configuration;

interface Command
{
    public function execute(Configuration $config): int;
}
