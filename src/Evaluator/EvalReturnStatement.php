<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Statements\ReturnStatement;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\ReturnValueObject;

final readonly class EvalReturnStatement
{
    public function __construct(
        public Evaluator $evaluator,
        public Environment $environment,
    ) {
    }

    public function __invoke(ReturnStatement $returnStatement): MonkeyObject
    {
        $monkeyObject = $this->evaluator->eval($returnStatement->value, $this->environment);

        if ($monkeyObject instanceof ErrorObject) {
            return $monkeyObject;
        }

        return new ReturnValueObject($monkeyObject);
    }
}
