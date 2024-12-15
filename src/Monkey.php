<?php

declare(strict_types=1);

namespace Monkey;

use Monkey\Evaluator\Environment;
use Monkey\Evaluator\Evaluator;
use Monkey\Lexer\Lexer;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;
use Monkey\Parser\Parser;
use Monkey\Parser\ProgramParser;
use RuntimeException;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

use function count;

use function in_array;
use function sprintf;

use const PHP_EOL;
use const STR_PAD_RIGHT;

final class Monkey
{
    private const string VERSION = '1.0.0';

    private readonly Environment $environment;

    private readonly OutputInterface $output;

    private bool $debugMode = false;

    private bool $showStats = false;

    private readonly float $startTime;

    private readonly int $startMemory;

    public function __construct()
    {
        $this->environment = new Environment();
        $this->startTime = microtime(true);
        $this->startMemory = memory_get_usage();

        $this->output = new ConsoleOutput();
        $this->output->getFormatter()->setStyle('title', new OutputFormatterStyle('white', null, ['bold']));
        $this->output->getFormatter()->setStyle('memory', new OutputFormatterStyle('green'));
        $this->output->getFormatter()->setStyle('peak', new OutputFormatterStyle('yellow'));
        $this->output->getFormatter()->setStyle('time', new OutputFormatterStyle('blue'));
    }

    /**
     * @param array<string> $argv
     */
    public function run(array $argv): int
    {
        if (count($argv) <= 1) {
            return $this->showHelp();
        }

        if (in_array('--debug', $argv)) {
            $this->debugMode = true;
            $argv = array_values(array_filter($argv, fn ($arg): bool => $arg !== '--debug'));
        }

        if (in_array('--stats', $argv)) {
            $this->showStats = true;
            $argv = array_values(array_filter($argv, fn ($arg): bool => $arg !== '--stats'));
        }

        try {
            // If we removed --debug and no other args remain, show help
            if (count($argv) <= 1) {
                return $this->showHelp();
            }

            $result = match ($argv[1]) {
                'repl' => $this->startRepl(),
                'run' => $this->runFile($argv[2] ?? null),
                '--version', '-v' => $this->showVersion(),
                '--help', '-h' => $this->showHelp(),
                default => $this->showHelp(),
            };

            if ($this->showStats) {
                $this->printPerformanceStats();
            }

            return $result;
        } catch (Throwable $throwable) {
            $this->writeError($throwable->getMessage());

            if ($this->showStats) {
                $this->printPerformanceStats();
            }

            return 1;
        }
    }

    private function printPerformanceStats(): void
    {
        $timeEnd = microtime(true);
        $memEnd = memory_get_usage();
        $memUsed = $memEnd - $this->startMemory;
        $peakMem = memory_get_peak_usage(true);
        $timeTaken = $timeEnd - $this->startTime;

        $this->output->writeln('');
        $this->output->writeln('');
        $this->output->writeln('<title>Performance Statistics</title>');

        $tableStyle = new TableStyle();
        $tableStyle
            ->setHorizontalBorderChars('-')
            ->setVerticalBorderChars('|')
            ->setCrossingChars('+', '+', '+', '+', '+', '+', '+', '+', '+')
            ->setPadType(STR_PAD_RIGHT);

        $table = new Table($this->output);
        $table->setStyle($tableStyle);

        $table->setRows([
            ['Memory used', "<memory>{$this->formatBytes($memUsed)}</memory>"],
            ['Peak memory', "<peak>{$this->formatBytes($peakMem)}</peak>"],
            ['Time taken', '<time>' . number_format($timeTaken, 6) . ' seconds</time>'],
        ]);

        $table->render();

        $this->output->writeln('');
    }

    private function writeOutput(MonkeyObject $result): void
    {
        // Skip output for NullObject unless in debug mode
        if ($result instanceof NullObject && !$this->debugMode) {
            return;
        }

        if ($this->debugMode) {
            $class = $result::class;
            $this->output->write("<comment>{$class}:</comment> ");
        }

        $this->output->writeln($result->inspect());
    }

    private function writeError(string $message): void
    {
        $this->output->writeln("<error>Error: {$message}</error>");
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return sprintf('%.2f %s', $bytes, $units[$pow]);
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

    private function handleQuit(): bool
    {
        if ($this->showStats) {
            $this->printPerformanceStats();
        }

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
            --stats           Show performance statistics

        Examples:
            monkey repl
            monkey run example.monkey
        HELP . PHP_EOL;

        return 0;
    }
}
