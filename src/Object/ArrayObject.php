<?php

declare(strict_types=1);

namespace Monkey\Object;

use Monkey\Ast\Expressions\Expression;

final class ArrayObject implements InternalObject
{
    /** @var array<Expression> */
    private array $elements;

    public function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    public function value(): array
    {
        return $this->elements;
    }

    public function type(): string
    {
        return self::ARRAY_OBJ;
    }

    public function inspect(): string
    {
        $elements = [];
        /** @var Expression $element */
        foreach ($this->elements as $element) {
            $elements[] = $element->toString();
        }

        return \sprintf('[%s]', \implode(', ', $elements));
    }
}
