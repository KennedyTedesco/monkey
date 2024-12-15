<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Command;

use MonkeyLang\Lang\Evaluator\Environment;
use MonkeyLang\Lang\Evaluator\Evaluator;
use MonkeyLang\Lang\Lexer\Lexer;
use MonkeyLang\Lang\Object\MonkeyObject;
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
        private PerformanceTracker $performanceTracker,
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

        if ($config->hasStats()) {
            $this->performanceTracker->start();
        }

        try {
            $result = $this->evaluateCode($contents);
            $this->outputFormatter->writeOutput($result, $config->hasDebug());

            if ($config->hasStats()) {
                $metrics = $this->performanceTracker->stop();
                $this->outputFormatter->writePerformanceStats($metrics);
            }

            return 0;
        } catch (Throwable $throwable) {
            $this->outputFormatter->writeError($throwable->getMessage());

            return 1;
        }
    }

    private function evaluateCode(string $contents): MonkeyObject
    {
        $lexer = new Lexer($contents);
        $parser = new Parser($lexer);

        if ($parser->errors() !== []) {
            throw new RuntimeException(
                "Parser errors:\n" . implode("\n", $parser->errors()),
            );
        }

        $program = new ProgramParser()($parser);
        $evaluator = new Evaluator();

        return $evaluator->eval($program, new Environment());
    }
}
