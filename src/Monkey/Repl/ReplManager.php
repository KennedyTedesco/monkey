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
use Symfony\Component\Console\Helper\Table;
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
            $this->welcome();

            while ($this->running) {
                $this->outputFormatter->write('');
                $input = $this->inputReader->readLine();

                if ($input === false) {
                    $this->outputFormatter->write('');
                    $this->outputFormatter->write("Goodbye!");

                    return 0;
                }

                if (trim($input) === '') {
                    continue;
                }

                if ($this->runCommand($input)) {
                    continue;
                }

                try {
                    $result = $this->evaluate($input);
                    
                    $this->outputFormatter->writeOutput($result, $this->debugMode);
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

    private function runCommand(string $input): bool
    {
        $trimmedInput = trim($input);

        return match ($trimmedInput) {
            ':q', ':quit' => $this->quit(),
            ':c', ':clear' => $this->clear(),
            default => false,
        };
    }

    private function quit(): bool
    {
        $this->running = false;
        $this->outputFormatter->write('');
        $this->outputFormatter->write("Goodbye!");

        return true;
    }

    private function clear(): bool
    {
        if (PHP_OS_FAMILY === 'Windows') {
            system('cls');
        } else {
            system('clear');
        }

        $this->welcome();

        return true;
    }

    private function welcome(): void
    {
        $table = new Table($this->outputFormatter->output);

        $table->setRows([
            ['<fg=green>🐒 Monkey Programming Language</> <fg=yellow>v1.0.0</>'],
            [''],
            ["<fg=cyan>Type</> <fg=blue>:c</> <fg=cyan>for clear,</> <fg=blue>:q</> <fg=cyan>to quit</>"],
        ]);

        $table->render();

        $this->outputFormatter->write('');
    }
}
