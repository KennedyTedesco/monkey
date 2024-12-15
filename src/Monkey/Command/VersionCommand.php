<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Command;

use MonkeyLang\Monkey\Config\Configuration;
use MonkeyLang\Monkey\IO\OutputFormatter;
use MonkeyLang\Monkey\Monkey;

final readonly class VersionCommand implements Command
{
    public function __construct(
        private OutputFormatter $outputFormatter,
    ) {
    }

    public function execute(Configuration $config): int
    {
        $this->outputFormatter->write('Monkey Programming Language v' . Monkey::version());

        return 0;
    }
}
