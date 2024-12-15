<?php

declare(strict_types=1);

namespace Monkey\Monkey\Command;

use Monkey\Monkey\Config\Configuration;

interface Command
{
    public function execute(Configuration $config): int;
}
