<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Config;

use RuntimeException;

use function count;
use function in_array;

final class ConfigurationManager
{
    private const array VALID_COMMANDS = ['repl', 'run', 'version', 'help'];

    private const array VALID_OPTIONS = ['--debug', '--stats'];

    /**
     * @param array<string> $argv
     */
    public function parseArguments(array $argv): Configuration
    {
        if (count($argv) <= 1) {
            $argv[] = 'help';
        }

        $options = [];
        $arguments = [];
        $command = '';

        foreach ($argv as $index => $arg) {
            if ($index === 0) {
                continue;
            }

            if (str_starts_with($arg, '--')) {
                if (!in_array($arg, self::VALID_OPTIONS)) {
                    throw new RuntimeException("Invalid option: {$arg}");
                }

                $options[trim($arg, '-')] = true;
            } elseif ($command === '') {
                if (!in_array($arg, self::VALID_COMMANDS)) {
                    throw new RuntimeException("Invalid command: {$arg}");
                }

                $command = $arg;
            } else {
                $arguments[] = $arg;
            }
        }

        return new Configuration($command, $options, $arguments);
    }
}
