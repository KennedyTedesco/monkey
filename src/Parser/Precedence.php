<?php

declare(strict_types=1);

namespace Monkey\Parser;

final class Precedence
{
    public const LOWEST = 0x1;
    public const OR = 0x2;
    public const AND = 0x3;
    public const EQUALS = 0x4;
    public const LESS_GREATER = 0x5;
    public const SUM = 0x6;
    public const PRODUCT = 0x7;
    public const PREFIX = 0x8;
    public const CALL = 0x9;
    public const INDEX = 0xA;
}
