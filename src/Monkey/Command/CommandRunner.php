<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Command;

use MonkeyLang\Monkey\Config\Configuration;

final readonly class CommandRunner
{
    public function __construct(
        private CommandFactory $commandFactory,
    ) {
    }

    public function execute(Configuration $config): int
    {
        $command = $this->commandFactory->create($config->command);

        return $command->execute($config);
    }
}
