<?php

declare(strict_types=1);

namespace Monkey\Monkey;

use Monkey\Monkey\Command\CommandRunner;
use Monkey\Monkey\Config\ConfigurationManager;
use Throwable;

use const STDERR;

final readonly class Monkey
{
    private const string VERSION = '1.0.0';

    public function __construct(
        private CommandRunner $commandRunner,
        private ConfigurationManager $configManager,
    ) {
    }

    public static function version(): string
    {
        return self::VERSION;
    }

    public function run(array $argv): int
    {
        try {
            $config = $this->configManager->parseArguments($argv);

            return $this->commandRunner->execute($config);
        } catch (Throwable $throwable) {
            fwrite(STDERR, "Error: {$throwable->getMessage()}\n");

            return 1;
        }
    }
}
