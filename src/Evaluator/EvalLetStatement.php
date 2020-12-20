<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Statements\LetStatement;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final class EvalLetStatement
{
    public function __construct(private Evaluator $evaluator, private Environment $env)
    {
    }

    public function __invoke(LetStatement $node): MonkeyObject
    {
        $value = $this->evaluator->eval($node->value(), $this->env);

        if ($value instanceof ErrorObject) {
            return $value;
        }

        $this->env->set($node->name()->value(), $value);

        return $this->evaluator->eval($node->name(), $this->env);
    }
}
