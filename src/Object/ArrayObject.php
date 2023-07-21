<?php

declare(strict_types=1);

namespace Monkey\Object;

final readonly class ArrayObject extends MonkeyObject
{
    public function __construct(
        private array $elements,
    ) {
    }

    public function value(): array
    {
        return $this->elements;
    }

    public function type(): int
    {
        return self::MO_ARRAY;
    }

    public function typeLiteral(): string
    {
        return 'ARRAY';
    }

    public function inspect(): string
    {
        $elements = [];

        /** @var MonkeyObject $element */
        foreach ($this->elements as $element) {
            $elements[] = $element->type() === self::MO_STRING ? '"' . $element->inspect() . '"' : $element->inspect();
        }

        return sprintf('[%s]', implode(', ', $elements));
    }
}
