<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\MonkeyObject;

final class Environment
{
    private ?Environment $outer;

    /** @var array<MonkeyObject> */
    private array $store = [];

    public function __construct(?self $outer = null)
    {
        $this->outer = $outer;
    }

    public static function new(): self
    {
        return new self();
    }

    public static function newEnclosed(self $outer): self
    {
        return new self($outer);
    }

    public function set(string $name, MonkeyObject $object): void
    {
        $this->store[$name] = $object;
    }

    public function get(string $name): MonkeyObject
    {
        return $this->store[$name] ?? $this->outer->get($name);
    }

    public function contains(string $name): bool
    {
        return \array_key_exists($name, $this->store) || (null !== $this->outer && $this->outer->contains($name));
    }
}
