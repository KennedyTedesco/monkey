<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Command;

use MonkeyLang\Monkey\Config\Configuration;
use MonkeyLang\Monkey\IO\OutputFormatter;

final readonly class VersionCommand implements Command
{
    public function __construct(
        private OutputFormatter $outputFormatter,
    ) {
    }

    public function execute(Configuration $config): int
    {
        $this->outputFormatter->write('Monkey Programming Language v 1.0');

        return 0;
    }
}
