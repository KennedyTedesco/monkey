<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Ast\Statements\ReturnStatement;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\MonkeyObject;
use MonkeyLang\Object\ReturnValueObject;

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
