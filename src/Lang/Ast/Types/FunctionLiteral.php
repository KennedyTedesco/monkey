<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Ast\Types;

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Ast\Expressions\IdentifierExpression;
use MonkeyLang\Lang\Ast\Statements\BlockStatement;
use MonkeyLang\Lang\Support\StringBuilder;
use MonkeyLang\Lang\Token\Token;

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
