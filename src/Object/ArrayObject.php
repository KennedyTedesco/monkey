<?php

declare(strict_types=1);

namespace Monkey\Object;

use Monkey\Ast\Expressions\Expression;

final class ArrayObject extends MonkeyObject
{
    public function __construct(
        /* @var array<Expression> */
        private array $elements
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
            $elements[] = self::MO_STRING === $element->type() ? '"'.$element->inspect().'"' : $element->inspect();
        }

        return sprintf('[%s]', implode(', ', $elements));
    }
}
