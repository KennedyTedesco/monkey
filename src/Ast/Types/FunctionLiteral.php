<?php

declare(strict_types=1);

namespace Monkey\Ast\Types;

use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Ast\Statements\BlockStatement;
use Monkey\Support\StringBuilder;
use Monkey\Token\Token;

use function count;

final class FunctionLiteral extends Expression
{
    public function __construct(
        Token $token,
        /* @var array<IdentifierExpression> */
        public readonly array $parameters,
        public readonly BlockStatement $body,
    ) {
        $this->token = $token;
    }

    public function toString(): string
    {
        $stringBuilder = StringBuilder::new($this->tokenLiteral())
            ->append('(');

        $count = count($this->parameters);

        foreach ($this->parameters as $index => $parameter) {
            $separator = $index !== $count - 1 ? ',' : '';

            $stringBuilder->append("{$parameter}{$separator}");
        }

        return $stringBuilder->append(") {$this->body}")->toString();
    }
}
