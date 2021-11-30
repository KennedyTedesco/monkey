<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Statements\ReturnStatement;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\ReturnValueObject;

final class EvalReturnStatement
{
    public function __construct(private Evaluator $evaluator, private Environment $environment)
    {
    }

    public function __invoke(ReturnStatement $returnStatement): MonkeyObject
    {
        $object = $this->evaluator->eval($returnStatement->returnValue(), $this->environment);

        if ($object instanceof ErrorObject) {
            return $object;
        }

        return new ReturnValueObject($object);
    }
}
