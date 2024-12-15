<?php

declare(strict_types=1);

namespace Monkey\Monkey\Command;

use Monkey\Monkey\Config\Configuration;
use Monkey\Monkey\Repl\ReplManager;

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
