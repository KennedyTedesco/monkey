<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Statements\AssignStatement;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final class EvalAssingStatement
{
    public function __construct(private Evaluator $evaluator, private Environment $env)
    {
    }

    public function __invoke(AssignStatement $node): MonkeyObject
    {
        $value = $this->evaluator->eval($node->value(), $this->env);

        if ($value instanceof ErrorObject) {
            return $value;
        }

        $name = $node->name()->value();
        $nameMonkeyObject = $this->env->get($name);

        if (null === $nameMonkeyObject) {
            return ErrorObject::identifierNotFound($name);
        }

        $this->env->set($name, $value);

        return $value;
    }
}
