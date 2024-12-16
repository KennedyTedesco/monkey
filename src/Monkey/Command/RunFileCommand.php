<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Command;

use MonkeyLang\Lang\Evaluator\Environment;
use MonkeyLang\Lang\Evaluator\Evaluator;
use MonkeyLang\Lang\Lexer\Lexer;
use MonkeyLang\Lang\Parser\Parser;
use MonkeyLang\Lang\Parser\ProgramParser;
use MonkeyLang\Monkey\Config\Configuration;
use MonkeyLang\Monkey\IO\OutputFormatter;
use MonkeyLang\Monkey\Performance\PerformanceTracker;
use RuntimeException;
use Throwable;

final readonly class RunFileCommand implements Command
{
    public function __construct(
        private OutputFormatter $outputFormatter,
    ) {
    }

    public function execute(Configuration $config): int
    {
        $filename = $config->getFilename();

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

        $lexer = new Lexer($contents);
        $parser = new Parser($lexer);

        if ($parser->errors() !== []) {
            throw new RuntimeException(
                "Parser errors:\n" . implode("\n", $parser->errors()),
            );
        }

        $program = new ProgramParser()($parser);

        $evaluatorPerformanceTracker = null;

        if ($config->hasStats()) {
            $evaluatorPerformanceTracker = new PerformanceTracker('Evaluator');
            $evaluatorPerformanceTracker->start();
        }

        try {
            $result = new Evaluator()->eval($program, new Environment());

            if ($config->hasStats()) {
                $evaluatorPerformanceMetrics = $evaluatorPerformanceTracker->stop();
            }

            $this->outputFormatter->writeOutput($result, $config->hasDebug());
            $this->outputFormatter->write('');
            $this->outputFormatter->write('');

            if ($config->hasStats()) {
                $this->outputFormatter->writePerformanceStats($evaluatorPerformanceMetrics);
            }

            return 0;
        } catch (Throwable $throwable) {
            $this->outputFormatter->writeError($throwable->getMessage());

            return 1;
        }
    }
}
