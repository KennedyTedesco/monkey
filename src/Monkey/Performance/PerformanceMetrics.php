<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Performance;

final readonly class PerformanceMetrics
{
    public function __construct(
        public string $title,
        public float $timeElapsed,
        public int $memoryUsed,
    ) {
    }
}
