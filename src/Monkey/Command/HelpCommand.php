<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Command;

use MonkeyLang\Monkey\Config\Configuration;
use MonkeyLang\Monkey\IO\OutputFormatter;

final readonly class HelpCommand implements Command
{
    public function __construct(
        private OutputFormatter $outputFormatter,
    ) {
    }

    public function execute(Configuration $config): int
    {
        $this->outputFormatter->write(<<<HELP
        Usage: monkey [command]

        Commands:
            repl              Start the interactive REPL
            run <file>        Execute a Monkey source file
            --version, -v     Show version information
            --help, -h        Show this help message
            --debug           Enable debug mode
            --stats          Show performance statistics

        Examples:
            monkey repl
            monkey run example.monkey
        HELP);

        return 0;
    }
}
