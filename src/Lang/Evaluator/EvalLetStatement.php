<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Ast\Statements\LetStatement;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\MonkeyObject;

final readonly class EvalLetStatement
{
    public function __construct(
        public Evaluator $evaluator,
        public Environment $environment,
    ) {
    }

    public function __invoke(LetStatement $letStatement): MonkeyObject
    {
        $monkeyObject = $this->evaluator->eval($letStatement->value, $this->environment);

        if ($monkeyObject instanceof ErrorObject) {
            return $monkeyObject;
        }

        $this->environment->set($letStatement->name->value, $monkeyObject);

        return $this->evaluator->eval($letStatement->name, $this->environment);
    }
}
