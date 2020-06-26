<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Statements\ReturnStatement;
use Monkey\Object\ErrorObject;
use Monkey\Object\InternalObject;
use Monkey\Object\ReturnValueObject;

final class EvalReturnStatement
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

    public function __invoke(ReturnStatement $node): InternalObject
    {
        $object = $this->evaluator->eval($node->returnValue(), $this->env);

        if ($object instanceof ErrorObject) {
            return $object;
        }

        return new ReturnValueObject($object);
    }
}
