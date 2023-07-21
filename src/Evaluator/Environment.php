<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\MonkeyObject;

final class Environment
{
    /** @var array<MonkeyObject> */
    private array $map = [];

    public function __construct(
        private readonly ?self $outer = null,
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
