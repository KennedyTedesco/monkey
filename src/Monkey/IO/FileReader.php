<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\IO;

use MonkeyLang\Monkey\Exceptions\MonkeyRuntimeException;

final class FileReader
{
    public function read(string $filename): string
    {
        if (!file_exists($filename)) {
            throw MonkeyRuntimeException::fileNotFound($filename);
        }

        $contents = file_get_contents($filename);

        if ($contents === false) {
            throw MonkeyRuntimeException::fileNotReadable($filename);
        }

        return $contents;
    }

    public function isReadable(string $filename): bool
    {
        return file_exists($filename) && is_readable($filename);
    }
}
