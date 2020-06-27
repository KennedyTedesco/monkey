<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Statements\AssignStatement;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final class EvalAssingStatement
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

    public function __invoke(AssignStatement $node): MonkeyObject
    {
        $value = $this->evaluator->eval($node->value(), $this->env);

        if ($value instanceof ErrorObject) {
            return $value;
        }

        $name = $node->name()->value();

        if (!$this->env->contains($name)) {
            return ErrorObject::identifierNotFound($name);
        }

        $this->env->set($name, $value);

        return $value;
    }
}
