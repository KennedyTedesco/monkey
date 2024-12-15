<?php

declare(strict_types=1);

namespace MonkeyLang\Monkey\IO;

use MonkeyLang\Monkey\Exceptions\MonkeyRuntimeException;

use function in_array;
use function sprintf;
use function strlen;

final class InputValidator
{
    public function validateNotEmpty(string $input): void
    {
        if (trim($input) === '') {
            throw new MonkeyRuntimeException('Input cannot be empty');
        }
    }

    public function validateMaxLength(string $input, int $maxLength): void
    {
        if (strlen($input) > $maxLength) {
            throw new MonkeyRuntimeException(
                sprintf('Input exceeds maximum length of %d characters', $maxLength),
            );
        }
    }

    public function validatePattern(string $input, string $pattern): void
    {
        if (in_array(preg_match($pattern, $input), [0, false], true)) {
            throw new MonkeyRuntimeException('Input format is invalid');
        }
    }
}
