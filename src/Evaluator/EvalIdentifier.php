<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Object\ErrorObject;
use Monkey\Object\InternalObject;

final class EvalIdentifier
{
    private Environment $env;

    public function __construct(Environment $env)
    {
        $this->env = $env;
    }

    public function __invoke(IdentifierExpression $node): InternalObject
    {
        if ($this->env->contains($node->value())) {
            return $this->env->get($node->value());
        }

        return ErrorObject::identifierNotFound($node->value());
    }
}
