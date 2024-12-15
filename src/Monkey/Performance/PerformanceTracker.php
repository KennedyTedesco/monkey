<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Performance;

use RuntimeException;

final class PerformanceTracker
{
    private ?float $startTime = null;

    private ?int $startMemory = null;

    public function __construct(
        private readonly string $title,
    ) {
    }

    public static function new(string $title): self
    {
        return new self($title);
    }

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
            title: $this->title,
            timeElapsed: microtime(true) - $this->startTime,
            memoryUsed: memory_get_usage() - $this->startMemory,
        );

        $this->startTime = null;
        $this->startMemory = null;

        return $metrics;
    }
}
