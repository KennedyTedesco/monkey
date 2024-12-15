<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\IO;

use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Object\NullObject;
use MonkeyLang\Monkey\Performance\PerformanceMetrics;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

use function count;
use function sprintf;

final readonly class OutputFormatter
{
    public function __construct(
        private(set) OutputInterface $output,
    ) {
        $this->configureStyles();
    }

    public function writeOutput(MonkeyObject $result, bool $debug = false): void
    {
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
        $this->output->writeln("<title>{$metrics->title} Performance Statistics</title>");
        $table = new Table($this->output);

        $table->setRows([
            ['Memory used', "\033[32m" . $this->formatBytes($metrics->memoryUsed) . "\033[0m"],
            ['Time taken', "\033[34m" . number_format($metrics->timeElapsed, 6) . " seconds\033[0m"],
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
