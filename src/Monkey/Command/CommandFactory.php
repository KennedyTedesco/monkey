<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Command;

use MonkeyLang\Monkey\IO\OutputFormatter;
use MonkeyLang\Monkey\Performance\PerformanceTracker;
use MonkeyLang\Monkey\Repl\ReplManager;
use RuntimeException;

final class CommandFactory
{
    /** @var array<string, Command> */
    private array $commands = [];

    public function __construct(
        private readonly ReplManager $replManager,
        private readonly OutputFormatter $outputFormatter,
        private readonly PerformanceTracker $performanceTracker,
    ) {
        $this->registerCommands();
    }

    public function create(string $commandName): Command
    {
        if (!isset($this->commands[$commandName])) {
            throw new RuntimeException("Unknown command: {$commandName}");
        }

        return $this->commands[$commandName];
    }

    private function registerCommands(): void
    {
        $this->commands = [
            'repl' => new ReplCommand($this->replManager),
            'run' => new RunFileCommand($this->outputFormatter, $this->performanceTracker),
            'help' => new HelpCommand($this->outputFormatter),
            'version' => new VersionCommand($this->outputFormatter),
        ];
    }
}
