<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\Exceptions;

use RuntimeException;

use function sprintf;

final class MonkeyRuntimeException extends RuntimeException
{
    public static function fileNotFound(string $filename): self
    {
        return new self(sprintf('File "%s" not found', $filename));
    }

    public static function fileNotReadable(string $filename): self
    {
        return new self(sprintf('File "%s" is not readable', $filename));
    }
}
