<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Statements\LetStatement;
use Monkey\Object\ErrorObject;
use Monkey\Object\InternalObject;

final class EvalLetStatement
{
    private Environment $env;
    private Evaluator $evaluator;

    public function __construct(
        Evaluator $evaluator,
        Environment $env
    ) {
        $this->env = $env;
        $this->evaluator = $evaluator;
    }

    public function __invoke(LetStatement $node): InternalObject
    {
        $value = $this->evaluator->eval($node->value(), $this->env);

        if ($value instanceof ErrorObject) {
            return $value;
        }

        $this->env->set($node->name()->value(), $value);

        return $this->evaluator->eval($node->name(), $this->env);
    }
}
