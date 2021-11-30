<?php

declare(strict_types=1);

namespace Monkey\Parser;

final class Precedence
{
    /**
     * @var int
     */
    public const LOWEST = 0x1;

    /**
     * @var int
     */
    public const OR = 0x2;

    /**
     * @var int
     */
    public const AND = 0x3;

    /**
     * @var int
     */
    public const EQUALS = 0x4;

    /**
     * @var int
     */
    public const LESS_GREATER = 0x5;

    /**
     * @var int
     */
    public const SUM = 0x6;

    /**
     * @var int
     */
    public const PRODUCT = 0x7;

    /**
     * @var int
     */
    public const PREFIX = 0x8;

    /**
     * @var int
     */
    public const CALL = 0x9;

    /**
     * @var int
     */
    public const INDEX = 0xA;

    /**
     * @var int
     */
    public const POWER = 0xB;
}
