<?php

declare(strict_types=1);

namespace Monkey\Ast\Types;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class ArrayLiteral extends Expression
{
    /** @var array<Expression> */
    private array $elements;

    public function __construct(Token $token, array $elements)
    {
        $this->token = $token;
        $this->elements = $elements;
    }

    public function elements(): array
    {
        return $this->elements;
    }

    public function toString(): string
    {
        $out = '';
        $elements = [];

        /** @var Expression $element */
        foreach ($this->elements as $element) {
            $elements[] = $element->toString();
        }

        if (\count($elements) > 0) {
            $out .= \implode(',', $elements);
        }

        return "[{$out}]";
    }
}
