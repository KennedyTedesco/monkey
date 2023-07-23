<?php

declare(strict_types=1);

namespace Monkey\Parser;

enum Precedence: int
{
    case LOWEST = 0x1;

    case OR = 0x2;

    case AND = 0x3;

    case EQUALS = 0x4;

    case LESS_GREATER = 0x5;

    case SUM = 0x6;

    case PRODUCT = 0x7;

    case PREFIX = 0x8;

    case CALL = 0x9;

    case INDEX = 0xA;

    case POWER = 0xB;
}
