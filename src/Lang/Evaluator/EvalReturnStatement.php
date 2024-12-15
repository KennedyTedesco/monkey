<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Ast\Statements\ReturnStatement;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Object\ReturnValueObject;

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
