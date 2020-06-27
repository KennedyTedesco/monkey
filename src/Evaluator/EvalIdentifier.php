<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final class EvalIdentifier
{
    private Environment $env;

    public function __construct(Environment $env)
    {
        $this->env = $env;
    }

    public function __invoke(IdentifierExpression $node): MonkeyObject
    {
        if ($this->env->contains($node->value())) {
            return $this->env->get($node->value());
        }

        if (BuiltinFunction::contains($node->value())) {
            return BuiltinFunction::get($node->value());
        }

        return ErrorObject::identifierNotFound($node->value());
    }
}
