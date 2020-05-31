<?php

declare(strict_types=1);

namespace Monkey\Parser;

final class Precedence
{
    public const LOWEST = 0x1;
    public const EQUALS = 0x2;
    public const LESSGREATER = 0x3;
    public const SUM = 0x4;
    public const PRODUCT = 0x5;
    public const PREFIX = 0x6;
    public const CALL = 0x7;
}
