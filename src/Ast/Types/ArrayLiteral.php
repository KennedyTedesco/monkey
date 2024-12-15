<?php

declare(strict_types=1);

namespace MonkeyLang\Ast\Types;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Support\StringBuilder;
use MonkeyLang\Token\Token;

use function count;

final class ArrayLiteral extends Expression
{
    /**
     * @param array<Expression> $elements
     */
    public function __construct(
        public readonly Token $token,
        public readonly array $elements,
    ) {
    }

    public function toString(): string
    {
        $stringBuilder = StringBuilder::new('[');

        $count = count($this->elements);

        foreach ($this->elements as $index => $element) {
            $separator = $index !== $count - 1 ? ',' : '';

            $stringBuilder->append("{$element}{$separator}");
        }

        return $stringBuilder->append(']')->toString();
    }
}
