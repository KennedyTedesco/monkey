<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Config;

final readonly class Configuration
{
    public function __construct(
        public string $command,
        /**
         * @var array<string, mixed>
         */
        public array $options,
        /**
         * @var array<string>
         */
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
