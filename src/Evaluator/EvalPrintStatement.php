<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Statements\PrintStatement;
use Monkey\Object\OutputObject;

final class EvalPrintStatement
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

    public function __invoke(PrintStatement $node): OutputObject
    {
        $object = $this->evaluator->eval($node->value(), $this->env);

        return new OutputObject($object->value());
    }
}
