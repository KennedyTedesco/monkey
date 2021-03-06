<?php

declare(strict_types=1);

namespace Monkey\Ast\Types;

use Monkey\Ast\Expressions\Expression;
use Monkey\Token\Token;

final class ArrayLiteral extends Expression
{
    public function __construct(Token $token, /* @var array<Expression> */
    private array $elements)
    {
        $this->token = $token;
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
