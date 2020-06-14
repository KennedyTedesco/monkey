<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Object\InternalObject;

final class Environment
{
    /** @var array<InternalObject> */
    private array $container;

    public function add(string $name, InternalObject $object): void
    {
        $this->container[$name] = $object;
    }

    public function get(string $name): InternalObject
    {
        return $this->container[$name];
    }

    public function contains(string $name): bool
    {
        return isset($this->container[$name]);
    }
}
