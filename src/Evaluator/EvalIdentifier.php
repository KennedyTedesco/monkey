<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Object\ErrorObject;
use Monkey\Object\InternalObject;

final class EvalIdentifier
{
    private Evaluator $evaluator;

    public function __construct(Evaluator $evaluator)
    {
        $this->evaluator = $evaluator;
    }

    public function __invoke(IdentifierExpression $node): InternalObject
    {
        $env = $this->evaluator->environment();

        if ($env->contains($node->value())) {
            return $env->get($node->value());
        }

        return ErrorObject::identifierNotFound($node->value());
    }
}
