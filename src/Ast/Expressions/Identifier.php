<?php

declare(strict_types=1);

namespace Monkey\Ast\Expressions;

use Monkey\Token\Token;

final class Identifier extends Expression
{
    public string $value;

    public function __construct(Token $token, string $value)
    {
        $this->token = $token;
        $this->value = $value;
    }

    public function toString(): string
    {
        return '';
    }
}
