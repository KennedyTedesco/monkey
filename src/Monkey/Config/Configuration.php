<?php

declare(strict_types=1);

namespace Monkey\Monkey\Config;

final readonly class Configuration
{
    public function __construct(
        public string $command,
        public array $options,
        public array $arguments,
    ) {
    }

    public function hasDebug(): bool
    {
        return isset($this->options['debug']);
    }

    public function hasStats(): bool
    {
        return isset($this->options['stats']);
    }

    public function getFilename(): ?string
    {
        return $this->arguments[0] ?? null;
    }
}
