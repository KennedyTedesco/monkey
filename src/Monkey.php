<?php

declare(strict_types=1);

namespace Monkey;

use JetBrains\PhpStorm\NoReturn;
use Monkey\Evaluator\Environment;
use Monkey\Evaluator\Evaluator;
use Monkey\Lexer\Lexer;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;
use Monkey\Parser\Parser;
use Monkey\Parser\ProgramParser;
use RuntimeException;
use Throwable;

use function count;

use const PHP_EOL;
use const STDERR;

final class Monkey
{
    private const string VERSION = '1.0.0';

    private readonly Environment $environment;

    private bool $debugMode = false;

    public function __construct()
    {
        $this->environment = new Environment();
    }

    /**
     * @param array<string> $argv
     */
    public function run(array $argv): int
    {
        if (count($argv) <= 1) {
            return $this->showHelp();
        }

        try {
            return match ($argv[1]) {
                'repl' => $this->startRepl(),
                'run' => $this->runFile($argv[2] ?? null),
                '--version', '-v' => $this->showVersion(),
                '--help', '-h' => $this->showHelp(),
                '--debug' => $this->enableDebugMode(),
                default => $this->showHelp(),
            };
        } catch (Throwable $throwable) {
            $this->writeError($throwable->getMessage());

            return 1;
        }
    }

    private function startRepl(): int
    {
        $this->showWelcomeBanner();

        while (true) {
            $input = $this->readInput();

            if ($input === false) {
                echo PHP_EOL . 'Goodbye!' . PHP_EOL;

                return 0;
            }

            if (trim($input) === '') {
                continue;
            }

            if ($this->handleSpecialCommand($input)) {
                continue;
            }

            try {
                $this->writeOutput($this->evaluate($input));
            } catch (Throwable $e) {
                $this->writeError($e->getMessage());
            }
        }
    }

    private function runFile(?string $filename): int
    {
        if ($filename === null) {
            throw new RuntimeException('No input file specified');
        }

        if (!file_exists($filename)) {
            throw new RuntimeException("File not found: {$filename}");
        }

        $contents = file_get_contents($filename);

        if ($contents === false) {
            throw new RuntimeException("Could not read file: {$filename}");
        }

        $this->writeOutput($this->evaluate($contents));

        return 0;
    }

    private function evaluate(string $input): MonkeyObject
    {
        $lexer = new Lexer($input);
        $parser = new Parser($lexer);

        $errors = $parser->errors();

        if ($errors !== []) {
            throw new RuntimeException("Parser errors:\n" . implode("\n", $errors));
        }

        $program = new ProgramParser()($parser);
        $evaluator = new Evaluator();

        return $evaluator->eval($program, $this->environment);
    }

    private function handleSpecialCommand(string $input): bool
    {
        return match (trim($input)) {
            ':q', ':quit', 'exit' => $this->handleQuit(),
            ':h', ':help' => $this->handleHelp(),
            ':c', ':clear' => $this->handleClear(),
            ':d', ':debug' => $this->handleDebugToggle(),
            default => false,
        };
    }

    #[NoReturn] private function handleQuit(): bool
    {
        echo 'Goodbye!' . PHP_EOL;

        exit(0);
    }

    private function handleHelp(): bool
    {
        echo <<<HELP
        \nMonkey REPL Commands:
        :q, :quit, exit   Exit the REPL
        :h, :help         Show this help message
        :c, :clear        Clear the screen
        :d, :debug        Toggle debug mode

        Press Ctrl+C to cancel current input
        HELP . PHP_EOL;

        return true;
    }

    private function handleClear(): bool
    {
        system('clear');

        $this->showWelcomeBanner();

        return true;
    }

    private function handleDebugToggle(): bool
    {
        $this->debugMode = !$this->debugMode;

        echo 'Debug mode: ' . ($this->debugMode ? 'Enabled' : 'Disabled') . PHP_EOL;

        return true;
    }

    private function showWelcomeBanner(): void
    {
        $version = self::VERSION;

        echo <<<BANNER
        🐒 Monkey Programming Language v{$version}
        Type ':h' for help, ':c' for clear, ':q' to quit
        BANNER . PHP_EOL . PHP_EOL;
    }

    private function readInput(): string | false
    {
        return readline("\n➜ ");
    }

    private function writeOutput(MonkeyObject $result): void
    {
        // Skip output for NullObject unless in debug mode
        if ($result instanceof NullObject && !$this->debugMode) {
            return;
        }

        if ($this->debugMode) {
            echo $result::class . ': ';
        }

        echo $result->inspect() . PHP_EOL;
    }

    private function writeError(string $message): void
    {
        fwrite(STDERR, 'Error: ' . $message . PHP_EOL);
    }

    private function showVersion(): int
    {
        echo 'Monkey Programming Language v' . self::VERSION . PHP_EOL;

        return 0;
    }

    private function showHelp(): int
    {
        echo <<<HELP
        Usage: monkey [command]

        Commands:
            repl              Start the interactive REPL
            run <file>        Execute a Monkey source file
            --version, -v     Show version information
            --help, -h        Show this help message
            --debug           Enable debug mode

        Examples:
            monkey repl
            monkey run example.monkey
        HELP . PHP_EOL;

        return 0;
    }

    private function enableDebugMode(): int
    {
        $this->debugMode = true;

        return $this->startRepl();
    }
}
