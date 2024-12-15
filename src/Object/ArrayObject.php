<?php

declare(strict_types=1);

namespace Monkey\Object;

use function count;
use function sprintf;

final readonly class ArrayObject extends MonkeyObject
{
    /**
     * @param array<MonkeyObject> $value
     */
    public function __construct(
        public array $value,
    ) {
    }

    public function type(): MonkeyObjectType
    {
        return MonkeyObjectType::ARRAY;
    }

    public function count(): int
    {
        return count($this->value);
    }

    public function inspect(): string
    {
        $elements = [];

        foreach ($this->value as $element) {
            $elements[] = $element->type() === MonkeyObjectType::STRING ? '"' . $element->inspect() . '"' : $element->inspect();
        }

        return sprintf('[%s]', implode(', ', $elements));
    }

    /**
     * @return array<MonkeyObject>
     */
    public function value(): array
    {
        return $this->value;
    }
}
