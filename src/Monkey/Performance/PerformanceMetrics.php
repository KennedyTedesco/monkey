<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Performance;

final readonly class PerformanceMetrics
{
    public function __construct(
        public float $timeElapsed,
        public int $memoryUsed,
        public int $peakMemory,
    ) {
    }
}
