<?php

declare(strict_types=1);

namespace Monkey\Monkey\Performance;

use RuntimeException;

final class PerformanceTracker
{
    private ?float $startTime = null;

    private ?int $startMemory = null;

    public function start(): void
    {
        $this->startTime = microtime(true);
        $this->startMemory = memory_get_usage();
    }

    public function stop(): PerformanceMetrics
    {
        if ($this->startTime === null || $this->startMemory === null) {
            throw new RuntimeException('Performance tracking not started');
        }

        $metrics = new PerformanceMetrics(
            timeElapsed: microtime(true) - $this->startTime,
            memoryUsed: memory_get_usage() - $this->startMemory,
            peakMemory: memory_get_peak_usage(true),
        );

        $this->startTime = null;
        $this->startMemory = null;

        return $metrics;
    }
}
