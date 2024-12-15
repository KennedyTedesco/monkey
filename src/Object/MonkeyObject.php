<?php

declare(strict_types=1);

namespace MonkeyLang\Object;

abstract readonly class MonkeyObject
{
    /**
     * Returns the type identifier for this object.
     */
    abstract public function type(): MonkeyObjectType;

    /**
     * Returns the underlying value of this object.
     *
     * @return mixed The actual value this object wraps
     */
    abstract public function value(): mixed;

    /**
     * Returns a string representation of this object for inspection.
     */
    abstract public function inspect(): string;

    /**
     * Returns a string representation of this object's type.
     */
    public function typeLiteral(): string
    {
        return $this->type()->toString();
    }
}
