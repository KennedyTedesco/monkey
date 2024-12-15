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
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Throwable;


use const PHP_OS_FAMILY;

final class ReplManager
{
    private bool $debugMode = false {
        get {
            return $this->debugMode;
        }
    }

    private bool $running = true;

    public function __construct(
        private readonly InputReader $inputReader,
        private readonly OutputFormatter $outputFormatter,
        private readonly PerformanceTracker $performanceTracker,
        private Environment $environment = new Environment() {
            get {
                return $this->environment;
            }
        },
        private readonly Evaluator $evaluator = new Evaluator(),
    ) {
    }

    public function start(Configuration $config): int
    {
        try {
            $this->debugMode = $config->hasDebug();
            $this->showWelcomeBanner();

            while ($this->running) {
                $this->outputFormatter->write('');
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

        $program = new ProgramParser()($parser);

        return $this->evaluator->eval($program, $this->environment);
    }

    private function handleSpecialCommand(string $input): bool
    {
        $trimmedInput = trim($input);

        $commands = [
            ':q' => fn(): bool => $this->handleQuit(),
            ':quit' => fn(): bool => $this->handleQuit(),
            ':c' => fn(): bool => $this->handleClear(),
            ':clear' => fn(): bool => $this->handleClear(),
        ];

        if (isset($commands[$trimmedInput])) {
            return $commands[$trimmedInput]();
        }

        return false;
    }

    private function handleQuit(): bool
    {
        $this->running = false;
        $this->outputFormatter->write('Goodbye!');

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

    private function showWelcomeBanner(): void
    {
        $table = new Table($this->outputFormatter->output);

        $table->setRows([
            ['🐒 Monkey Programming Language v1.0.0'],
            ["Type ':c' for clear, ':q' to quit"],
        ]);

        $table->render();

        $this->outputFormatter->write('');
    }
}
