<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Repl;

use MonkeyLang\Lang\Evaluator\Environment;
use MonkeyLang\Lang\Evaluator\Evaluator;
use MonkeyLang\Lang\Lexer\Lexer;
use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Parser\Parser;
use MonkeyLang\Lang\Parser\ProgramParser;
use MonkeyLang\Monkey\Config\Configuration;
use MonkeyLang\Monkey\Exceptions\MonkeyRuntimeException;
use MonkeyLang\Monkey\IO\InputReader;
use MonkeyLang\Monkey\IO\OutputFormatter;
use MonkeyLang\Monkey\Performance\PerformanceTracker;
use Throwable;

use const PHP_OS_FAMILY;

final class ReplManager
{
    private const array SPECIAL_COMMANDS = [
        ':q' => 'handleQuit',
        ':quit' => 'handleQuit',
        'exit' => 'handleQuit',
        ':h' => 'handleHelp',
        ':help' => 'handleHelp',
        ':c' => 'handleClear',
        ':clear' => 'handleClear',
        ':d' => 'handleDebugToggle',
        ':debug' => 'handleDebugToggle',
        ':r' => 'handleResetEnvironment',
        ':reset' => 'handleResetEnvironment',
    ];

    private bool $debugMode = false;

    private bool $running = true;

    public function __construct(
        private readonly InputReader $inputReader,
        private readonly OutputFormatter $outputFormatter,
        private readonly PerformanceTracker $performanceTracker,
        private Environment $environment = new Environment(),
        private readonly Evaluator $evaluator = new Evaluator(),
    ) {
    }

    public function start(Configuration $config): int
    {
        try {
            $this->debugMode = $config->hasDebug();
            $this->showWelcomeBanner();

            while ($this->running) {
                $this->outputFormatter->write(''); // Add newline before prompt
                $input = $this->inputReader->readLine();

                if ($input === false) {
                    $this->outputFormatter->write("\nGoodbye!");

                    return 0;
                }

                if (trim($input) === '') {
                    continue;
                }

                if ($this->handleSpecialCommand($input)) {
                    continue;
                }

                try {
                    $this->evaluateAndOutput($input, $config);
                } catch (MonkeyRuntimeException $e) {
                    $this->outputFormatter->writeError($e->getMessage());
                } catch (Throwable $e) {
                    $this->outputFormatter->writeError("Unexpected error: {$e->getMessage()}");

                    if ($this->debugMode) {
                        $this->outputFormatter->write($e->getTraceAsString());
                    }
                }
            }

            return 0;
        } catch (Throwable $throwable) {
            $this->outputFormatter->writeError("Fatal error: {$throwable->getMessage()}");

            if ($this->debugMode) {
                $this->outputFormatter->write($throwable->getTraceAsString());
            }

            return 1;
        }
    }

    public function isDebugMode(): bool
    {
        return $this->debugMode;
    }

    public function getEnvironment(): Environment
    {
        return $this->environment;
    }

    private function evaluateAndOutput(string $input, Configuration $config): void
    {
        if ($config->hasStats()) {
            $this->performanceTracker->start();
        }

        $result = $this->evaluate($input);
        $this->outputFormatter->writeOutput($result, $this->debugMode);

        if ($config->hasStats()) {
            $metrics = $this->performanceTracker->stop();
            $this->outputFormatter->writePerformanceStats($metrics);
        }
    }

    private function evaluate(string $input): MonkeyObject
    {
        $lexer = new Lexer($input);
        $parser = new Parser($lexer);

        $errors = $parser->errors();

        if ($errors !== []) {
            throw new MonkeyRuntimeException(
                "Parser errors:\n" . implode("\n", $errors),
            );
        }

        $program = (new ProgramParser())($parser);

        return $this->evaluator->eval($program, $this->environment);
    }

    private function handleSpecialCommand(string $input): bool
    {
        $trimmedInput = trim($input);

        if (isset(self::SPECIAL_COMMANDS[$trimmedInput])) {
            $method = self::SPECIAL_COMMANDS[$trimmedInput];

            return $this->{$method}();
        }

        return false;
    }

    private function handleQuit(): bool
    {
        $this->running = false;
        $this->outputFormatter->write('Goodbye!');

        return true;
    }

    private function handleHelp(): bool
    {
        $this->outputFormatter->write(<<<HELP
        \nMonkey REPL Commands:
        :q, :quit, exit   Exit the REPL
        :h, :help         Show this help message
        :c, :clear        Clear the screen
        :d, :debug        Toggle debug mode
        :r, :reset        Reset the environment

        Press Ctrl+C to cancel current input
        HELP);

        return true;
    }

    private function handleClear(): bool
    {
        if (PHP_OS_FAMILY === 'Windows') {
            system('cls');
        } else {
            system('clear');
        }

        $this->showWelcomeBanner();

        return true;
    }

    private function handleDebugToggle(): bool
    {
        $this->debugMode = !$this->debugMode;
        $this->outputFormatter->write(
            'Debug mode: ' . ($this->debugMode ? 'Enabled' : 'Disabled'),
        );

        return true;
    }

    private function handleResetEnvironment(): bool
    {
        $this->environment = new Environment();
        $this->outputFormatter->write('Environment has been reset');

        return true;
    }

    private function showWelcomeBanner(): void
    {
        $this->outputFormatter->write(<<<BANNER
                    __,__
           .--.  .-"     "-.  .--.
          / .. \\/  .-. .-.  \\/ .. \\
         | |  '|  /   Y   \\  |'  | |
         | \\   \\  \\ 0 | 0 /  /   / |
          \\ '- ,\\.-"`` ``"-./, -' /
           `'-' /_   ^ ^   _\\ '-'`
               |  \\._   _./  |
               \\   \\ `~` /   /
                '._ '-=-' _.'
                   '~---~'
        -------------------------------
        | Monkey Programming Language |
        -------------------------------

        Type ':h' for help, ':c' for clear, ':q' to quit
        BANNER);
        $this->outputFormatter->write('');
    }
}
