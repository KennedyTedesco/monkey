<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Statements\PrintStatement;
use Monkey\Object\NullObject;

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

    public function __invoke(PrintStatement $node): NullObject
    {
        $object = $this->evaluator->eval($node->value(), $this->env);

        echo $object->value();

        return NullObject::instance();
    }
}
