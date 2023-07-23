<?php

declare(strict_types=1);

namespace Monkey\Support;

use Stringable;

final class StringBuilder implements Stringable
{
    public function __construct(
        protected string $string = '',
    ) {
    }

    public static function new(string | Stringable $str = ''): self
    {
        return new self((string)$str);
    }

    public function append(string | Stringable $str): self
    {
        $this->string .= $str;

        return $this;
    }

    public function appendSpace(): self
    {
        $this->string .= ' ';

        return $this;
    }

    public function toString(): string
    {
        return $this->string;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
