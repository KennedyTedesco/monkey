<?php

declare(strict_types=1);

namespace Monkey\Object;

use function count;

final readonly class ArrayObject extends MonkeyObject
{
    public function __construct(
        public array $value,
    ) {
    }

    public function type(): int
    {
        return self::MO_ARRAY;
    }

    public function count(): int
    {
        return count($this->value);
    }

    public function typeLiteral(): string
    {
        return 'ARRAY';
    }

    public function inspect(): string
    {
        $elements = [];

        /** @var MonkeyObject $element */
        foreach ($this->value as $element) {
            $elements[] = $element->type() === self::MO_STRING ? '"' . $element->inspect() . '"' : $element->inspect();
        }

        return sprintf('[%s]', implode(', ', $elements));
    }
}
