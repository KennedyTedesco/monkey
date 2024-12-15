<?php

declare(strict_types=1);

namespace MonkeyLang\Ast\Types;

use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Ast\Expressions\IdentifierExpression;
use MonkeyLang\Ast\Statements\BlockStatement;
use MonkeyLang\Support\StringBuilder;
use MonkeyLang\Token\Token;

use function count;

final class FunctionLiteral extends Expression
{
    /**
     * @param array<IdentifierExpression> $parameters
     */
    public function __construct(
        public readonly Token $token,
        public readonly array $parameters,
        public readonly BlockStatement $body,
    ) {
    }

    public function toString(): string
    {
        $stringBuilder = StringBuilder::new($this->token->literal)
            ->append('(');

        $count = count($this->parameters);

        foreach ($this->parameters as $index => $parameter) {
            $separator = $index !== $count - 1 ? ',' : '';

            $stringBuilder->append("{$parameter}{$separator}");
        }

        return $stringBuilder->append(") {$this->body}")->toString();
    }
}
