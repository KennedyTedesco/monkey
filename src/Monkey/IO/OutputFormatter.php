<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\IO;

use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Object\NullObject;
use MonkeyLang\Monkey\Performance\PerformanceMetrics;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Output\OutputInterface;

use function count;
use function sprintf;

use const STR_PAD_RIGHT;

final readonly class OutputFormatter
{
    public function __construct(
        private OutputInterface $output,
    ) {
        $this->configureStyles();
    }

    public function writeOutput(MonkeyObject $result, bool $debug = false): void
    {
        // Skip output for NullObject unless in debug mode
        if ($result instanceof NullObject && !$debug) {
            return;
        }

        if ($debug) {
            $class = $result::class;
            $this->output->write("<comment>{$class}:</comment> ");
        }

        $this->output->writeln($result->inspect());
    }

    public function writeError(string $message): void
    {
        $this->output->writeln("<error>Error: {$message}</error>");
    }

    public function write(string $message): void
    {
        $this->output->writeln($message);
    }

    public function writePerformanceStats(PerformanceMetrics $metrics): void
    {
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
            ['Memory used', "<memory>{$this->formatBytes($metrics->memoryUsed)}</memory>"],
            ['Peak memory', "<peak>{$this->formatBytes($metrics->peakMemory)}</peak>"],
            ['Time taken', '<time>' . number_format($metrics->timeElapsed, 6) . ' seconds</time>'],
        ]);

        $table->render();

        $this->output->writeln('');
    }

    private function configureStyles(): void
    {
        $this->output->getFormatter()->setStyle(
            'title',
            new OutputFormatterStyle('white', null, ['bold']),
        );
        $this->output->getFormatter()->setStyle(
            'memory',
            new OutputFormatterStyle('green'),
        );
        $this->output->getFormatter()->setStyle(
            'peak',
            new OutputFormatterStyle('yellow'),
        );
        $this->output->getFormatter()->setStyle(
            'time',
            new OutputFormatterStyle('blue'),
        );
        $this->output->getFormatter()->setStyle(
            'prompt',
            new OutputFormatterStyle('cyan'),
        );
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
}
