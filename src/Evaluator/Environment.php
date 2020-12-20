<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\MonkeyObject;

final class Environment
{
    /** @var array<MonkeyObject> */
    private array $map = [];

    public function __construct(
        private ?self $outer = null
    ) {
    }

    public function set(string $name, MonkeyObject $object): void
    {
        $this->map[$name] = $object;
    }

    public function get(string $name): ?MonkeyObject
    {
        return $this->map[$name] ?? (null !== $this->outer ? $this->outer->get($name) : null);
    }

    public function contains(string $name): bool
    {
        return \array_key_exists($name, $this->map) || ($this->outer && $this->outer->contains($name));
    }
}
