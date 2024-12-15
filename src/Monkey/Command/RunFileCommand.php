<?php

declare(strict_types=1);

namespace Monkey\Monkey\Command;

use Monkey\Evaluator\Environment;
use Monkey\Evaluator\Evaluator;
use Monkey\Lexer\Lexer;
use Monkey\Monkey\Config\Configuration;
use Monkey\Monkey\IO\OutputFormatter;
use Monkey\Monkey\Performance\PerformanceTracker;
use Monkey\Object\MonkeyObject;
use Monkey\Parser\Parser;
use Monkey\Parser\ProgramParser;
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
