<?php

declare(strict_types=1);

namespace Monkey\Ast\Types;

use Monkey\Ast\Expressions\Expression;
use Monkey\Support\StringBuilder;
use Monkey\Token\Token;

use function count;

final class ArrayLiteral extends Expression
{
    public function __construct(
        Token $token,
        /* @var array<Expression> */
        public readonly array $elements,
    ) {
        $this->token = $token;
    }

    public function toString(): string
    {
        $stringBuilder = StringBuilder::new('[');

        $count = count($this->elements);

        foreach ($this->elements as $index => $element) {
            $stringBuilder->append($element);

            if ($index !== $count - 1) {
                $stringBuilder->append(',');
            }
        }

        $stringBuilder->append(']');

        return $stringBuilder->toString();
    }
}
