<?php

declare(strict_types=1);

namespace Monkey\Lexer;

final class Input
{
    private int $size;
    private string $input;

    public function __construct(string $input)
    {
        $this->input = $input;
        $this->size = \mb_strlen($input);
    }

    public function char(int $position): string
    {
        return $this->input[$position];
    }

    public function substr(int $start, int $length): string
    {
        return \mb_substr($this->input, $start, $length);
    }

    public function size(): int
    {
        return $this->size;
    }
}
