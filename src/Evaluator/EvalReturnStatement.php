<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Statements\ReturnStatement;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\ReturnValueObject;

final class EvalReturnStatement
{
    public function __construct(private Evaluator $evaluator, private Environment $env)
    {
    }

    public function __invoke(ReturnStatement $node): MonkeyObject
    {
        $object = $this->evaluator->eval($node->returnValue(), $this->env);

        if ($object instanceof ErrorObject) {
            return $object;
        }

        return new ReturnValueObject($object);
    }
}
