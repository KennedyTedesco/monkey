<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\InternalObject;

final class Environment
{
    private ?Environment $outer;

    /** @var array<InternalObject> */
    private array $store;

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

    public function set(string $name, InternalObject $object): void
    {
        $this->store[$name] = $object;
    }

    public function get(string $name): InternalObject
    {
        return $this->store[$name] ?? $this->outer->get($name);
    }

    public function contains(string $name): bool
    {
        return isset($this->store[$name]);
    }
}
