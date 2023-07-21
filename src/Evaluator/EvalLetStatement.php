<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Statements\LetStatement;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final readonly class EvalLetStatement
{
    public function __construct(
        private Evaluator $evaluator,
        private Environment $environment,
    ) {
    }

    public function __invoke(LetStatement $letStatement): MonkeyObject
    {
        $monkeyObject = $this->evaluator->eval($letStatement->value(), $this->environment);

        if ($monkeyObject instanceof ErrorObject) {
            return $monkeyObject;
        }

        $this->environment->set($letStatement->name()->value(), $monkeyObject);

        return $this->evaluator->eval($letStatement->name(), $this->environment);
    }
}
