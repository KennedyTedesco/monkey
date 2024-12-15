<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Config;

use RuntimeException;

use function count;
use function in_array;

final class ConfigurationManager
{
    private const array VALID_COMMANDS = ['repl', 'run', '--version', '-v', '--help', '-h'];

    private const array VALID_OPTIONS = ['--debug', '--stats'];

    public function parseArguments(array $argv): Configuration
    {
        if (count($argv) <= 1) {
            throw new RuntimeException('No command specified');
        }

        $options = [];
        $arguments = [];
        $command = '';

        foreach ($argv as $index => $arg) {
            if ($index === 0) {
                continue;
            } // Skip script name

            if (str_starts_with((string)$arg, '--')) {
                if (!in_array($arg, self::VALID_OPTIONS)) {
                    throw new RuntimeException("Invalid option: {$arg}");
                }

                $options[trim((string)$arg, '-')] = true;
            } elseif ($command === '') {
                if (!in_array($arg, self::VALID_COMMANDS)) {
                    throw new RuntimeException("Invalid command: {$arg}");
                }

                $command = $arg;
            } else {
                $arguments[] = $arg;
            }
        }

        // If no command was found after processing options
        if ($command === '') {
            $command = '--help';
        }

        return new Configuration($command, $options, $arguments);
    }
}
