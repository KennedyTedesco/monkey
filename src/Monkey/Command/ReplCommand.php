<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Command;

use MonkeyLang\Monkey\Config\Configuration;
use MonkeyLang\Monkey\Repl\ReplManager;

final readonly class ReplCommand implements Command
{
    public function __construct(
        private ReplManager $replManager,
    ) {
    }

    public function execute(Configuration $config): int
    {
        return $this->replManager->start($config);
    }
}
