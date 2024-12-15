<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Object\MonkeyObject;

final class Environment
{
    /** @var array<MonkeyObject> */
    public array $map = [];

    public function __construct(
        public readonly ?self $outer = null,
    ) {
    }

    public function set(string $name, MonkeyObject $monkeyObject): void
    {
        $this->map[$name] = $monkeyObject;
    }

    public function get(string $name): ?MonkeyObject
    {
        return $this->map[$name] ?? $this->outer?->get($name);
    }
}
